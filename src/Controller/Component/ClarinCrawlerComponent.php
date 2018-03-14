<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use simple_html_dom;

/**
 * Description of ClarinCrawlerComponent
 *
 * @author Jesús Serna
 */
class ClarinCrawlerComponent extends BaseCrawlerComponent{   

    private $secciones_diario = [
        'policiales' => 'POLICIALES',
        'deportes' => 'DEPORTES',
        'política' => 'POLITICA',        
        'mundo' => 'INTERNACIONALES',
        'sociedad' => 'SOCIEDAD',
        'ciudades' => 'NACIONALES',
        'cultura' => 'SOCIEDAD',
        'next' => 'TECNOLOGIA',
        'extra show' => 'ESPECTACULO',
        'ieco' => 'ECONOMIA',
        'buena vida' => 'SOCIEDAD',
        'todoviajes' => 'SOCIEDAD'
        ];

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
            if($state['ok'])
            {
                $noticias = @simplexml_load_file($rss->url);
                if(!$noticias)return $guardados[$this->codigo][]= "No se pudo acceder al rss";
                if (isset($noticias)) {
                    foreach ($noticias->channel->item as $noticia) {
                        if($this->existTitle((string)$noticia->title) && !in_array(trim($noticia->title),$guardados[$this->codigo]))
                        {
                            try{
                                @$this->setHtmlDomFromString((string)$noticia->link, $this->getStreamContext());  
                                if($this->html){
                                    $articulo = $this->articulosTable->newEntity();
                                    $articulo->publicado = $this->verificarIntegridadFechaNoticia($this->getFechaPublicadoRss((string)$noticia->pubDate));
                                    $articulo->titulo = (string)$noticia->title;
                                    $articulo->descripcion = $this->getDescripcion();
                                    $articulo->texto = $this->getContenido();
                                    $articulo->creado = date("Y-m-d H:i:s");
                                    $articulo->categoria_id = $this->getCategorias();
                                    $articulo->portal_id = $rss->portal_id;
                                    $articulo->habilitado = true;
                                    $articulo->url_rss = (string)$noticia->link;
                                    $articulo->visitas = 0;

                                    if ($this->articulosTable->save($articulo)) {
                                        if(count($palabras_claves = $this->getPalabrasClave())> 0 ){
                                            $articulo = $this->articulosTable->get($articulo->id);
                                            $articulo->palabras_claves = $palabras_claves;
                                            $this->articulosTable->save($articulo);
                                        }
                                        $imagenes = [];
                                        if(isset($noticia->enclosure) && isset($noticia->enclosure->attributes()->url)){
                                            $imagenes = [["titulo" => null, "path" =>(string)$noticia->enclosure->attributes()->url]];
                                        }
                                        else{
                                            $imagenes = $this->getImagenes();
                                        }
                                        foreach($imagenes as $imagen){
                                            $imagen_path = $imagen['path'];
                                            $imagen_name = explode("/", $imagen['path']);
                                            end($imagen_name);
                                            $imagen = $this->saveImagen($imagen_path, '', current($imagen_name),false);
                                            if($imagen != null){
                                                $this->getConnection()->insert('articulo_imagen', ['articulo_id' => $articulo->id,'imagen_id' => $imagen->id]);
                                            }
                                        }
                                        $guardados[$this->codigo][] = trim($articulo->titulo);
                                    }
                                }
                            }
                            catch(Exception $e){}
                        }
                    }
                }
            }
            else{
                $guardados[$this->codigo][]=$state['state'];
            }
        }
        return $guardados;
    }

    public function getDescripcion(){
        $descripcion = "";
        $data1 = $this->html->find("meta[name='twitter:description']", 0);

        if($data1 != null){
            $descripcion = $data1->attr['content'];
            return $descripcion;
        }
        else{
            throw new Exception("No hay descripcion de noticia");
        }
    }
    
    /*public function getContenido(){
        $texto = "";

        $data2 = $this->html->find('div.nota', 0);
        if(!is_null($data2)){
            $data2_p = $data2->getElementsByTagName('p');

            foreach($data2_p as $data_){
                $texto_p = strip_tags($data_->innertext,'<b><strong><em>'); 
                $texto_p = preg_replace('/\s\s+|&nbsp;/', '', $texto_p);
                if($texto_p != "" && $texto_p != " "){
                    $texto .= '<p>'.strip_tags(preg_replace('#<div id="mediaplayer(.*?)</div>#','',$data_->innertext),'<b><strong><em><br>').'</p>';
                }
            }
            return $texto;
        }
        else{
            throw new Exception("No hay contenido de noticia");
        }
    }*/

    public function getContenido(){
        $texto = "";

        $data2 = $this->html->find('div.nota', 0);
        if(!is_null($data2)){
            foreach($data2->children as $data){
                //imagenes
                if($data->tag == 'img' && strpos($data->attr['class'], "Image") !== false && !is_null($data->attr['src'])){
                    $texto .= "<img src='" . $data->attr['src'] . "' style='display: block; margin: 0 auto;' height='70%' width='70%'>#";
                    //obtenemos el caption de la foto
                    if(!is_null($data->attr['alt']) ){
                        $texto .= '<p align="center">' . '<i>' . $data->attr['alt'] . '</i>' . '</p>#';
                    }
                }
                //videos de Youtube
                elseif($data->tag == "div" && isset($data->attr['class']) && strpos($data->attr['class'], "Youtube") !== false && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "youtube") !== false){
                    $video_src_you = $data->find('iframe', 0);
                    $height_you = $video_src_you->attr['height'];
                    $width_you = $video_src_you->attr['width'];
                    if($width_you > 900){
                        $height_you = 480;
                        $width_you = (480*$video_src_you->attr['width'])/$video_src_you->attr['height'];
                    }
                    $texto .= "<iframe width='" . $width_you . "' height='" . $height_you . "' src='" . $video_src_you->attr['src'] . "' frameborder='0' allowfullscreen style='display: block; margin: 0 auto;'></iframe>#";
                }
                //videos de JW Player
                elseif($data->tag == "div" && isset($data->attr['class']) && strpos($data->attr['class'], "Media") !== false && !is_null($data->find('div', 0)) && strpos($data->find('div', 0)->attr['id'], "mediaplayer_") !== false){
                    //http://www.clarin.com/embed/video_CLAVID20160808_0033.html
                    $video_src_a = $data->find('div', 0);
                    $url_video = "http://www.clarin.com/embed/" . substr(str_replace('mediaplayer', 'video', $video_src_a->attr['id']), 0, -4) . ".html";
                    $texto .= "<iframe src='" . $url_video . "'  width='36%' height='260' scrolling='no' allowfullscreen style='display: block; margin: 0 auto;'></iframe>#";
                }
                elseif($data->tag == "p" && !is_null($data->find('div', 0)) && strpos($data->find('div', 0)->attr['class'], "Media") !== false){
                    $video_src_a = $data->find('div', 0);
                    if(!is_null($video_src_a->find('div', 0)) && strpos($video_src_a->find('div', 0)->attr['id'], "mediaplayer_") !== false){
                        $video_src_b = $video_src_a->find('div', 0);
                        $url_video = "http://www.clarin.com/embed/" . substr(str_replace('mediaplayer', 'video', $video_src_b->attr['id']), 0, -4) . ".html";
                        $texto .= "<iframe src='" . $url_video . "'  width='36%' height='260' scrolling='no' allowfullscreen style='display: block; margin: 0 auto;'></iframe>#";
                    }
                }
                //imagenes en p
                elseif($data->tag == "p" && !is_null($data->find('img', 0)) && strpos($data->find('img', 0)->attr['class'], "Image") !== false){
                    $image_src = $data->find('img', 0);
                    if(strpos($image_src->attr['src'], "../..") !== false){
                        $texto .= "<img src='" . str_replace("../..", "http://www.clarin.com", $image_src->attr['src']) . "' style='display: block; margin: 0 auto;' height='70%' width='70%'>#";
                    }
                    else{
                        $texto .= "<img src='" . $image_src->attr['src'] . "' style='display: block; margin: 0 auto;' height='70%' width='70%'>#";
                    }
                    //obtenemos el caption de la foto
                    if(!is_null($image_src->attr['alt']) ){
                        $texto .= '<p align="center">' . '<i>' . $image_src->attr['alt'] . '</i>' . '</p>#';
                    }
                }
                //iframes especiales
                elseif($data->tag == "iframe" && isset($data->attr['src']) && strpos($data->attr['src'], "redaccionfiler") !== false){
                    $texto .= "<iframe width='50%' height='" . ($data->attr['height'] + 22) . "' src='" . $data->attr['src'] . "' frameborder='0' style='display: block; margin: 0 auto;'></iframe>#";
                }
                //estado de Facebook
                elseif($data->tag == "iframe" && strpos($data->attr['src'], "facebook") !== false && strpos($data->attr['src'], "post") !== false){
                    $texto .= "<iframe width='" . $data->attr['width'] . "' height='" . $data->attr['height'] . "' allowtransparency='true' src='" . $data->attr['src'] . "' frameborder='0' scrolling='no' style='display: block; margin: 0 auto; border: none; overflow: hidden'></iframe>#";
                }
                //estado de Twitter
                elseif($data->tag == "blockquote" && strpos($data->attr['class'], "twitter-tweet") !== false && count($data->find('a')) > 0){
                    $twitter_src = [];
                    $twitter_srcs_foto = $data->find('a');
                    foreach($twitter_srcs_foto as $twitter_src_foto){
                        if(strpos($twitter_src_foto->attr['href'], "t.co") !== false || strpos($twitter_src_foto->attr['href'], "status") !== false){
                            array_push($twitter_src, $twitter_src_foto);
                        }
                    }
                    if(count($twitter_src) == 1 && strpos(end($twitter_src)->attr['href'], "twitter") !== false){
                        $texto .= "<blockquote align='center' class='twitter-tweet' data-lang='es'><p lang='es' dir='ltr'><a href='" . end($twitter_src)->attr['href'] . "'></a></p></blockquote><script async src='//platform.twitter.com/widgets.js' charset='utf-8'></script>#";
                    }
                    else{
                        $texto .= "<blockquote align='center' class='twitter-tweet' data-lang='en'><p lang='es' dir='ltr'><a href='" . $twitter_src[0]->attr['href'] . "'></a></p><a href='" . end($twitter_src)->attr['href'] . "'></a></blockquote><script async src='//platform.twitter.com/widgets.js' charset='utf-8'></script>#";
                    }
                }
                //timeline de Storify
                elseif($data->tag == "div" && isset($data->attr['class']) && strpos($data->attr['class'], "storify") !== false && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "storify") !== false){
                    $storify_src = $data->find('iframe', 0);
                    $texto .= "<iframe src='" . $storify_src->attr['src'] . "' frameborder='no' allowtransparency='true' scrolling='yes' style='display: block; margin: 0 auto; border: solid #f2f2f2 4px; border-radius: 10px; overflow: hidden; width: 810px; height: 800px; background-color: transparent;'></iframe>#";
                }
                //estado de Instagram
                elseif($data->tag == "blockquote" && strpos($data->attr['class'], "instagram-media") !== false){
                    $instagram_src = $data->find('a', 0);
                    $texto .= "<blockquote class='instagram-media' data-instgrm-captioned data-instgrm-version='7' style=' background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 0px auto; max-width:658px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);'><div style='padding:8px;'> <div style=' background:#F8F8F8; line-height:0; margin-top:40px; padding:55.6954436451% 0; text-align:center; width:100%;'></div><p style=' margin:8px 0 0 0; padding:0 4px;'><a href='" . $instagram_src->attr['href'] . "' style=' color:#000; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none; word-wrap:break-word;' target='_blank'></a></p><p style=' color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;'></p></div></blockquote><script async defer src='//platform.instagram.com/en_US/embeds.js'></script>#<br/>";
                }
                //Google Maps
                elseif($data->tag == "div" && isset($data->attr['class']) && strpos($data->attr['class'], "Gmaps") !== false && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "google") !== false && strpos($data->find('iframe', 0)->attr['src'], "maps") !== false){
                    $gmaps_src = $data->find('iframe', 0);
                    $width_gmaps = $gmaps_src->attr['width'];
                    $height_gmaps = $gmaps_src->attr['height'];
                    $texto .= "<iframe width='" . $width_gmaps . "' height='" . $height_gmaps . "' src='" . $gmaps_src->attr['src'] . "' frameborder='0' style='display:block; margin: 0 auto; border:0' allowfullscreen=''></iframe>#";
                }
                //timeline de knightlab
                elseif($data->tag == "p" && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "knightlab") !== false){
                    $knightlab_src = $data->find('iframe', 0);
                    $texto .= "<iframe width='50%' height='650' src='" . $knightlab_src->attr['src'] . "' frameborder='no' allowtransparency='true' style='display: block; margin: 0 auto;'></iframe>#";
                }
                //texto plano
                elseif($data->tag == "p" && !is_null($data->find('text', 0)) && !is_null($data->find('text', 0)->innertext) && is_null($data->find('a', 0))){
                    $texto_p = strip_tags($data->innertext,'<b><strong><em>'); 
                    $texto_p = preg_replace('/\s\s+|&nbsp;/', '', $texto_p);
                    if($texto_p != "" && $texto_p != " " && strpos($texto_p, "Mirá también:") !== true){
                        $texto .= '<p>' . strip_tags(preg_replace('#<div id="mediaplayer(.*?)</div>#', '', $data->innertext),'<b><strong><em><br>') . '</p>';
                    }
                }
                else{
                    $texto .= '';
                }
            }
        }
        else{
            throw new Exception("No hay contenido de noticia.");
        }

        return $texto;
    }

    public function getImagenes(){
        $imagenes = [];

        $data3 = $this->html->find('#inline-content', 0);
        if(!is_null($data3)){
            //obtenemos el JSON del tag
            $data31 = $data3->find('script', 0)->find('text', 0)->innertext;
            $json = end(explode('chargeImages', $data31));
            $json_sin_inicio = str_replace(".chargeImage([", "", $json);
            $json_explode_sin_final = "[" . trim(reset(explode(']);', $json_sin_inicio))) . "]";
            //convertimos a JSON
            $images_decoded = json_decode($json_explode_sin_final);
            if(!is_null($images_decoded)){
                foreach($images_decoded as $image){
                    $image_src = reset(explode("alt=", trim($image->img)));
                    $imagenes[] = [
                        'path' => substr(str_replace('"', '', str_replace('src=', '', str_replace('<img', '', $image_src))), 1, -1),
                        'descripcion' => trim($image->title)
                        ];
                }
            }
            else{
                $data32 = $this->html->find('meta[property="og:image"]', 0);
                if(!is_null($data32) && isset($data32->attr['content'])){   
                    $imagenes[] = [
                        'path' => trim($data32->attr['content']),
                        'descripcion' => ''
                        ];
                }
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

        //es una estructura de noticia seccion normal
        if(count($this->html->find('div.breadcrumb ul',0)) > 0){
            if($this->html->find('div.breadcrumb ul',0)->find('li')){
                $seccion = $this->html->find('div.breadcrumb ul',0)->find('li',1)->plaintext;
                if(array_key_exists(strtolower(trim($seccion)), $this->secciones_diario)){
                    $codigo = $this->secciones_diario[strtolower(trim($seccion))];
                }
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
        $palabras_claves = BaseCrawlerComponent::getPalabrasClaves(1);

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

    public function testNoticia(){
        set_time_limit(0);

        $state = $this->getStateHeaderXml("http://www.clarin.com/"); 
        if($state['ok']){
            $urls = \Cake\Core\Configure::read('urls');
            foreach($urls as $url){
                @$this->setHtmlDomFromString($url, $this->getStreamContext());
                $articulo = TableRegistry::get('Articulos')->newEntity();
                $articulo->texto = $this->getContenido();
                $articulo->titulo = "TITULO";
                $articulo->descripcion = "DESCRIPCION";
                $articulo->creado = date("Y-m-d H:i:s");
                $articulo->categoria_id = 12;
                $articulo->publicado = date("Y-m-d H:i:s");
                $articulo->portal_id = 7;
                $articulo->habilitado = true;
                $articulo->url_rss = html_entity_decode(trim($url));
                $articulo->visitas = 0;
                TableRegistry::get('Articulos')->save($articulo);
            }
        }
    }
}
