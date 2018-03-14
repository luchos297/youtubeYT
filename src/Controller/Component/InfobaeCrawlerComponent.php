<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use simple_html_dom;

/**
 * Description of InfobaeCrawlerComponent
 *
 * @author Jesus Serna
 */
class InfobaeCrawlerComponent extends BaseCrawlerComponent{   
    
    private $secciones_diario = [
        'política' => 'POLITICA',
        'mundo' => 'INTERNACIONALES',
        'américa latina' => 'INTERNACIONALES',
        'internacional' => 'INTERNACIONALES',
        'sociedad' => 'SOCIEDAD',
        'tendencias' => 'SOCIEDAD',
        'nutriglam' => 'SOCIEDAD',
        'playtv' => 'SOCIEDAD',
        'mascotas' => 'SOCIEDAD',
        'lifestyle' => 'SOCIEDAD',
        'pasó en la tv' => 'ESPECTACULO',
        'vidriera' => 'ESPECTACULO',
        'teleshow' => 'ESPECTACULO',
        'infoshow' => 'ESPECTACULO',
        'tecno' => 'TECNOLOGIA',
        'ciencia' => 'TECNOLOGIA',
        'finanzas' => 'ECONOMIA',
        'economía' => 'ECONOMIA',
        'negocios' => 'ECONOMIA',
        'finanzas y negocios' => 'ECONOMIA',
        'finanzas & negocios' => 'ECONOMIA',
        'playfútbol' => 'DEPORTES',
        'primera' => 'DEPORTES',
        'río 2016' => 'DEPORTES',
        'deportes' => 'DEPORTES',
        ];

    private $excluye_seccion = ['espacio no editorial'];  

    public function runCrawler(){
        set_time_limit(0);
        $this->rssesTable = TableRegistry::get('Rsses');
        $this->articulosTable = TableRegistry::get('Articulos');
        $this->imagenesTable = TableRegistry::get('Imagenes');

        $rss = $this->rssesTable->find()
                ->where(['habilitado', 'Portales.codigo' => $this->codigo ])
                ->contain(['Portales'])
                ->first();

        $guardados = [$this->codigo =>[]];
        if(!is_null($rss)){
            $state = $this->getStateHeaderXml($rss->url); 
            if($state['ok']){
                $noticias = @simplexml_load_file($rss->url);
                if(!$noticias)return $guardados[$this->codigo][] = "No se pudo acceder al RSS";
                if(isset($noticias)){                
                    //limite debido a que el RSS contiene 1000+ entradas
                    $i = 0;
                    foreach($noticias->channel->item as $noticia)if ($i < 200){ 
                        if($this->existTitle((string)$noticia->title) && !in_array(trim($noticia->title),$guardados[$this->codigo])){
                            try{
                                @$this->setHtmlDomFromUrl((string)$noticia->link);
                                if($this->html){
                                    $this->clearNode('script');
                                    $articulo = $this->articulosTable->newEntity();
                                    $articulo->publicado = $this->verificarIntegridadFechaNoticia($this->getFechaPublicadoRss((string)$noticia->pubDate));
                                    $articulo->titulo = trim((string)$noticia->title);
                                    $articulo->descripcion = trim(strip_tags((string)$noticia->description));
                                    $articulo->texto = $this->getContenido();
                                    //$articulo->palabras_claves = trim((string)$noticia->title);
                                    $articulo->creado = date("Y-m-d H:i:s");
                                    $articulo->categoria_id = $this->getCategorias();
                                    $articulo->portal_id = $rss->portal_id;
                                    $articulo->habilitado = true;
                                    $articulo->url_rss = trim((string)$noticia->link);
                                    $articulo->visitas = 0;

                                    if($articulo->texto != "" && $articulo->titulo != "" && $articulo->descripcion != ""){
                                        if($this->articulosTable->save($articulo)){
                                            if(count($palabras_claves = $this->getPalabrasClave())> 0 ){
                                                $articulo = $this->articulosTable->get($articulo->id);
                                                $articulo->palabras_claves = $palabras_claves;
                                                $this->articulosTable->save($articulo);
                                            }
                                            @$this->setHtmlDomFromUrl((string)$noticia->link); 
                                            $imagenes = $this->getImagenes();
                                            foreach($imagenes as $imagen){
                                                $imagen_path = $imagen['path'];
                                                $imagen_caption = $imagen['descripcion'];

                                                if(preg_match('/.+\.(jpeg|jpg)/', $imagen_path)){
                                                    $imagen_name = ['x.jpg'];
                                                }
                                                elseif (preg_match('/.+\.(png)/', $imagen_path)){
                                                    $imagen_name = ['x.png'];
                                                }
                                                elseif (preg_match('/.+\.(gif)/', $imagen_path)){
                                                    $imagen_name = ['x.gif'];
                                                }
                                                else{
                                                    throw new Exception("Formato no soportado");
                                                }

                                                end($imagen_name);
                                                $imagen = $this->saveImagen($imagen_path, $imagen_caption, current($imagen_name), false);
                                                if(!is_null($imagen)){
                                                    $this->getConnection()->insert('articulo_imagen', ['articulo_id' => $articulo->id, 'imagen_id' => $imagen->id]);
                                                }
                                            }
                                            $guardados[$this->codigo][] = trim($articulo->titulo);
                                        }
                                    }
                                }
                            }
                            catch(Exception $e){}
                        }
                    }
                }
            }
            else{
                $guardados[$this->codigo]=$state['state'];
            }
        }
        return $guardados;
    }

    /*public function getContenido(){
        $texto = "";
        $this->clearNode('.embed_cont');
        $this->clearNode('footer');
        $temp_html = $this->clearNodeDom('.social-hori', $this->html);
        $temp_html = $this->clearNodeDom('.hmedia', $temp_html);
        $temp_html = $this->clearNodeDom('.videocontainer', $temp_html);
        $temp_html = $this->clearNodeDom('comment', $temp_html);
        $temp_html = $this->clearNodeDom('.slider', $temp_html);

        if($temp_html->find('.cuerposmart', 0) != null && $temp_html->find('.cuerposmart', 0)->find('div',0) != null){
            $data2 = $temp_html->find('.cuerposmart', 0)->getElementByTagName('div');           
            $count_p = 0;
            $count_div = 0;
            $count_nn = 0;

            if(count($data2->childNodes()) > 0){
                foreach($data2->childNodes() as $child){
                    if($child->tag == 'div' or $child->tag == 'p'){
                        $texto_p = strip_tags($child->innertext); 
                        $texto_p = preg_replace('/\s\s+|&nbsp;/', '', $texto_p);
                        if($texto_p != "" && $texto_p != " "){
                            $texto .= '<p>'.strip_tags($child->innertext,'<b><strong><h3>').'</p>';
                        }
                    }
                    else{
                        $count_nn++;
                    }

                    if($count_nn > 4){
                        throw new Exception("Contenido de noticia irregular");
                    }
                }
            }
            else{
                throw new Exception("No hay contenido de noticia");
            }

            return $texto;
        }
        else{
            throw new Exception("No hay contenido de noticia");
        }
    }*/

    public function getContenido(){
        $texto = "";

        $data2 = $this->html->find('#article-content', 0);
        if(is_null($data2) && empty($data2)){
            $data2 = reset($this->html->find('.cuerposmart', 0));
        }
        if(!is_null($data2->children)){
            //si tiene galeria, la eliminamos
            $data2_children = $data2->children;
            if(reset($data2_children)->tag == "div" && isset(reset($data2_children)->attr['class']) && strpos(reset($data2_children)->attr['class'], "slider") !== false){
                unset($data2_children[0]);
                $data2_children = array_values($data2_children);
            }
            foreach($data2_children as $data){
                //imagenes
                if($data->tag == 'div' && !is_null($data->find('figure', 0)) && strpos($data->find('figure', 0)->attr['class'], "image") !== false){
                    $image_src = $data->find('figure', 0);
                    if(!is_null($image_src->find('img', 0))){
                        $image_src = $data->find('img', 0);
                        $texto .= "<img src='" . $image_src->attr['data-original'] . "' style='display: block; margin: 0 auto;' height='70%' width='70%'>#";
                        if(!is_null($image_src->attr['alt'])){
                            $texto .= '<p align="center"><i>' . $image_src->attr['alt'] . '</i></p>#';
                        }
                    }
                }
                elseif($data->tag == 'figure' && !is_null($data->find('img', 0)) && strpos($data->find('img', 0)->attr['src'], "image") !== false){
                    $image_src = $data->find('figure', 0);
                    if(!is_null($image_src->find('img', 0))){
                        $image_src = $data->find('img', 0);
                        $texto .= "<img src='" . $image_src->attr['src'] . "' style='display: block; margin: 0 auto;' height='70%' width='70%'>#";
                        if(!is_null($image_src->attr['alt'])){
                            $texto .= '<p align="center"><i>' . $image_src->attr['alt'] . '</i></p>#';
                        }
                    }
                }
                //videos de Youtube
                elseif($data->tag == 'div' && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "youtube") !== false){
                    $iframes = $data->find('iframe');
                    foreach($iframes as $iframe){
                        $texto .= "<iframe width='720' height='" . $iframe->attr['height'] . "' src='" . $iframe->attr['src'] . "' frameborder='0' allowfullscreen style='display: block; margin: 0 auto;'></iframe>#";
                        if(!is_null($data->plaintext)){
                            $texto .= '<p>' . str_replace("Embed", "", $data->plaintext) . '</p>#';
                        }
                    }
                }
                //videos de Vine
                elseif($data->tag == 'p' && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "vine") !== false){
                    $video_src_vine = $data->find('iframe', 0);
                    $height_vine = $video_src_vine->attr['height'];
                    $width_vine = $video_src_vine->attr['width'];
                    if($width_vine > 900){
                        $height_vine = 480;
                        $width_vine = (480*$video_src_vine->attr['width'])/$video_src_vine->attr['height'];
                    }
                    $url_vine_final = str_replace("http", "https", $video_src_vine->attr['src']) . "/embed/simple";
                    $texto .= "<iframe width='" . $width_vine . "' height='" . $height_vine . "' src='" . $url_vine_final . "' frameborder='0' style='display: block; margin: 0 auto;'></iframe>#";
                }
                //videos embebidos
                elseif($data->tag == 'div' && isset($data->attr['class']) && strpos($data->attr['class'], "videocontainer") !== false && !is_null($data->find('figure', 0)) && strpos($data->find('figure', 0)->attr['class'], "video") !== false){
                    //http://youtu.be/Ls9Cg8iaq1s
                    //https://www.youtube.com/embed/Ls9Cg8iaq1s
                    $video_src_embed = $data->find('figure', 0);
                    $url_embed_final = str_replace("http://youtu.be", "https://www.youtube.com/embed", $video_src_embed->attr['data-media']);
                    $texto .= "<iframe width='65%' height='500' allowtransparency='true' src='" . $url_embed_final . "' frameborder='0' scrolling='no' style='display: block; margin: 0 auto;'></iframe>#";
                }
                //audio de tipo audio
                elseif($data->tag == 'div' && !is_null($data->find('.type_audio', 0))){
                    $div_audio = $data->find('.type_audio', 0);
                    if(!is_null($div_audio->find('iframe', 0)) && strpos($div_audio->find('iframe', 0)->attr['src'], "Adjunto") !== false){
                        $audio_src = $div_audio->find('iframe', 0);
                        $texto .= "<iframe width='50%' height='70' allowtransparency='true' src='" . "http://www.infobae.com" . $audio_src->attr['src'] . "' frameborder='no' scrolling='no' style='display: block; margin: 0 auto;'></iframe>#";    
                    }
                }
                //audio de tipo video
                elseif($data->tag == 'div' && !is_null($data->find('.type_video', 0))){
                    $div_audio = $data->find('.type_video', 0);
                    if(!is_null($div_audio->find('iframe', 0)) && strpos($div_audio->find('iframe', 0)->attr['src'], "Adjunto") !== false){
                        $audio_src = $div_audio->find('iframe', 0);
                        $texto .= "<iframe width='50%' height='70' allowtransparency='true' src='" . "http://www.infobae.com" . $audio_src->attr['src'] . "' frameborder='no' scrolling='no' style='display: block; margin: 0 auto;'></iframe>#";    
                    }
                }
                //estado de Twitter
                elseif($data->tag == 'div' && !is_null($twitter = $data->find('blockquote', 0)) && strpos($twitter->attr['class'], "twitter-tweet") !== false && count($twitter->find('a')) > 0){
                    /*if(!is_null($plano = $data->find('p', 0)) && isset($plano->attr['class']) && strpos($plano->attr['class'], "element-paragraph") !== false){
                        $texto_plano = $data->find('p', 0);
                        $texto_plano = preg_replace("/<\/?div[^>]*\>/i", "", trim($texto_plano->innertext));
                        $texto_plano = preg_replace('/\s\s+|&nbsp;/', '', $texto_plano);
                        if($texto_plano != "" && $texto_plano != " "){
                            $texto .= '<p>' . $texto_plano . '</p>#';
                        }
                    }*/
                    $twitter_src = [];
                    $twitter_srcs_foto = $twitter->find('a');
                    foreach($twitter_srcs_foto as $twitter_src_foto){
                        if(strpos($twitter_src_foto->attr['href'], "t.co") !== false || strpos($twitter_src_foto->attr['href'], "status") !== false){
                            array_push($twitter_src, $twitter_src_foto);
                        }
                    }
                    if(count($twitter_src) == 1 && strpos(end($twitter_src)->attr['href'], "twitter") !== false){
                        $texto .= "<blockquote align='center' class='twitter-tweet' data-lang='es' style='display: block; margin: 0 auto;'><p lang='es' dir='ltr'><a href='" . end($twitter_src)->attr['href'] . "'></a></p></blockquote><script async src='//platform.twitter.com/widgets.js' charset='utf-8'></script>#";
                    }
                    else{
                        $texto .= "<blockquote align='center' class='twitter-tweet' data-lang='en' style='display: block; margin: 0 auto;'><p lang='es' dir='ltr'><a href='" . $twitter_src[0]->attr['href'] . "'></a></p><a href='" . end($twitter_src)->attr['href'] . "'></a></blockquote><script async src='//platform.twitter.com/widgets.js' charset='utf-8'></script>#";
                    }
                }
                //timeline de Storify
                elseif($data->tag == "div" && !is_null($data->find('.storify', 0))){
                    $div_storify = $data->find('.storify', 0);
                    if(!is_null($div_storify->find('iframe', 0)) && strpos($div_storify->find('iframe', 0)->attr['src'], "storify") !== false){
                        $storify_src = $div_storify->find('iframe', 0);
                        $texto .= "<iframe src='" . $storify_src->attr['src'] . "' frameborder='no' allowtransparency='true' scrolling='yes' style='display: block; margin: 0 auto; border: solid #f2f2f2 4px; border-radius: 10px; overflow: hidden; width: 810px; height: 800px; background-color: transparent;'></iframe>#";
                    }
                }
                //estado de Instagram
                elseif($data->tag == "div" && !is_null($data->find('blockquote', 0)) && strpos($data->find('blockquote', 0)->attr['class'], "instagram-media") !== false){
                    $instagram_src = $data->find('a', 0);
                    $texto .= "<blockquote class='instagram-media' data-instgrm-captioned data-instgrm-version='7' style=' background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 0px auto; max-width:658px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);'><div style='padding:8px;'> <div style=' background:#F8F8F8; line-height:0; margin-top:40px; padding:55.6954436451% 0; text-align:center; width:100%;'></div><p style=' margin:8px 0 0 0; padding:0 4px;'><a href='" . $instagram_src->attr['href'] . "' style=' color:#000; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none; word-wrap:break-word;' target='_blank'></a></p><p style=' color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;'></p></div></blockquote><script async defer src='//platform.instagram.com/en_US/embeds.js'></script>#<br/>";
                }
                //mapas y street view de Google
                elseif($data->tag == "div" && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "google") !== false && strpos($data->find('iframe', 0)->attr['src'], "maps") !== false){
                    $gmaps_src = $data->find('iframe', 0);
                    $width_gmaps = $gmaps_src->attr['width'];
                    $height_gmaps = $gmaps_src->attr['height'];
                    $texto .= "<iframe width='" . $width_gmaps . "' height='" . $height_gmaps . "' src='" . $gmaps_src->attr['src'] . "' frameborder='0' style='display:block; margin: 0 auto; border:0' allowfullscreen=''></iframe>#";
                }
                //quote
                elseif($data->tag == 'p' && !is_null($data->find('span', 0)) && strpos($data->find('span', 0)->attr['class'], "Emphasis") !== false){
                    $quote_src = $data->find('span', 0);
                    $texto_h2 = strip_tags(trim($quote_src->innertext),'<b><strong>'); 
                    $texto_h2 = preg_replace('/\s\s+|&nbsp;/', '', $texto_h2);
                    if($texto_h2 != "" && $texto_h2 != " "){
                        $texto .= "<blockquote class='style-2'><p><h3>'" . strip_tags($texto_h2, '<b><strong>') . "'</h3></p></blockquote>#";
                    }
                }
                elseif($data->tag == 'div' && !is_null($data->find('blockquote', 0)) && strpos($data->find('blockquote', 0)->attr['class'], "blockquote") !== false){
                    $quote_src = $data->find('blockquote', 0);
                    $texto_h2 = strip_tags(trim($quote_src->innertext),'<b><strong>'); 
                    $texto_h2 = preg_replace('/\s\s+|&nbsp;/', '', $texto_h2);
                    if($texto_h2 != "" && $texto_h2 != " "){
                        $texto .= "<blockquote class='style-2'><p><h3>'" . strip_tags($texto_h2, '<b><strong>') . "'</h3></p></blockquote>#";
                    }
                }
                //texto resaltado
                /*elseif($data->tag == "div" && !is_null($data->find('span', 0)) && strpos($data->find('span', 0)->attr['class'], "text-highlight") !== false){
                    $highlight_src = $data->find('span', 0);
                    $texto_resaltado = preg_replace("/<\/?div[^>]*\>/i", "", trim($highlight_src->innertext));
                    $texto_resaltado = preg_replace('/\s\s+/', '', $texto_resaltado);
                    if($texto_resaltado != "" && $texto_resaltado != " "){
                        $texto .= "<span class='marker' style='background-color: rgb(220, 193, 0);'>" . $texto_resaltado . "</span>#";
                    }
                }*/
                //texto plano
                elseif($data->tag == "div" && !is_null($data->find('text', 0)) && !is_null($data->find('text', 0)->innertext) && is_null($data->find('span', 0)) || $data->tag == "p"){
                    $texto_plano = preg_replace("/<\/?div[^>]*\>/i", "", trim($data->innertext));
                    $texto_plano = preg_replace('/\s\s+|&nbsp;/', '', $texto_plano);
                    if($texto_plano != "" && $texto_plano != " "){
                        $texto .= '<p>' . $texto_plano . '</p>#';
                    }
                }
                else{
                    $texto .= "";
                }
            }
        }

        return $texto;
    }

    public function getImagenes(){
        $imagenes = [];

        $this->clearNode('.clone');
        $data3_galeria = $this->html->find('.slides', 0);
        if(!is_null($data3_galeria) && !is_null($data3_galeria->children)){
            foreach($data3_galeria->children as $picture){
                $img_src = $picture->find('img', 0);
                if(!is_null($img_src)){
                    $imagenes[] = [
                        'path' => trim($img_src->attr['src']),
                        'descripcion' => trim($img_src->attr['alt'])
                        ];
                }
            }
        }
        else{
            $data3 = $this->html->find('meta[property="og:image"]', 0);
            if(!is_null($data3) && isset($data3->attr['content'])){   
                $imagenes[] = [
                    'path' => trim($data3->attr['content']),
                    'descripcion' => ''
                    ];
            }
        }

        return $imagenes;
    }

    public function getFechaPublicadoRss($date_string){

        $date_pub = strtotime($date_string);
        return date("Y-m-d H:i:s", $date_pub);
    }

    public function getCategorias(){
        $codigo = 'EXTRA';

        $data4 =  $this->html->find('.hed-first', 0);
        if(!is_null($data4)){
            $data41 = $data4->find('a', 0);
            $seccion = $data41->plaintext;
            if(array_key_exists(strtolower(trim($seccion)), $this->secciones_diario)){
                $codigo = $this->secciones_diario[strtolower(trim($seccion))];
            }
        }

        $categoria_id = TableRegistry::get('Categorias')
                        ->find()
                        ->where(['Categorias.codigo' => $codigo])
                        ->first()
                        ->id; 

        return $categoria_id;
    }

    public function getPalabrasClave(){ 
        $array_palabras_claves = [];
        $palabras_claves = BaseCrawlerComponent::getPalabrasClaves();
        if(!is_null($palabras_claves) && isset($palabras_claves->attr['content'])){
            $lista_palabras = explode(',', $palabras_claves->attr['content']);

            //Se quita las repeticiones no case-sensitive
            $lista_palabras = array_intersect_key(
                    $lista_palabras,
                    array_unique(array_map("strtolower",$lista_palabras))
                    );

            foreach($lista_palabras as $palabra){
                if(($palabra = trim($palabra)) == ""){
                    continue;
                }
                $palabra_clave_existente = TableRegistry::get('PalabrasClaves')->findByTexto($palabra)->first();
                if($palabra_clave_existente){
                    $palabra_clave = $palabra_clave_existente;
                }
                else{
                    $palabra_clave = TableRegistry::get('PalabrasClaves')->newEntity();
                    $palabra_clave->texto = $palabra;
                    $palabra_clave->creado = date("Y-m-d H:i:s");
                }

                array_push($array_palabras_claves,$palabra_clave);
            }
        }
        return $array_palabras_claves;
    }
    
    public function getLinksPortada(){
        $links = [];

        try{
            $this->clearNode('.header-wrapper');
            $this->clearNode('.menu-wapper');
            $this->clearNode('.footer-wrapper');
            $this->clearNode('.banner');
            $this->clearNode('.separator');
            $this->clearNode('script');

            $portada_items = $this->html->find('.section-title');
            if(!is_null($portada_items)){                
                foreach($portada_items as $article){
                    if(!is_null($article->find('.title', 0)) && !is_null($article->find('.title', 0)->find('a', 0))){
                        $links[] = [
                            'titulo' => html_entity_decode($article->find('.title', 0)->find('a', 0)->plaintext),
                            'link' => $article->find('.title', 0)->find('a', 0)->href,
                            'seccion' => $article->find('.section', 0)->plaintext
                            ];
                    }
                }
            }

        }
        catch (Exception $e) {}

        return $links;
    }

    public function testNoticia(){
        set_time_limit(0);

        $state = $this->getStateHeaderXml("http://www.infobae.com/"); 
        if($state['ok']){
            $urls = \Cake\Core\Configure::read('urls');
            foreach($urls as $url){
                @$this->setHtmlDomFromString($url, $this->getStreamContext());
                $articulo = TableRegistry::get('Articulos')->newEntity();
                $articulo->texto = $this->getContenido();
                $articulo->titulo = "TITULO";
                $articulo->descripcion = "DESCRIPCION";
                $articulo->creado = date("Y-m-d H:i:s");
                $articulo->categoria_id = $this->getCategoria();
                $articulo->publicado = date("Y-m-d H:i:s");
                $articulo->portal_id = 8;
                $articulo->habilitado = true;
                $articulo->url_rss = html_entity_decode(trim($url));
                $articulo->visitas = 0;
                $imagenes = $this->getImagenes();
                TableRegistry::get('Articulos')->save($articulo);
            }
        }
    }
}