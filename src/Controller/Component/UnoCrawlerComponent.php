<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;

/**
 * Description of UnoCrawlerComponent
 *
 * @author Jesús Serna
 */
class UnoCrawlerComponent extends BaseCrawlerComponent{   
    
    private $secciones_diario = [
        'policiales' => 'POLICIALES',
        'ovacion' => 'DEPORTES',
        'cholunotas' => 'ESPECTACULO',
        'sociales' => 'SOCIALES',
        'a fondo' => 'SOCIEDAD',
        'a-fondo' => 'SOCIEDAD',
        'mundo' => 'INTERNACIONALES',
        'país' => 'NACIONALES',
        'pais' => 'NACIONALES',
        'mundo insólito' => 'SOCIEDAD',
        'mundo-insólito' => 'SOCIEDAD',
        'salud' => 'SOCIEDAD',
        'tecnología' => 'TECNOLOGIA',
        'espectáculos' => 'ESPECTACULO',
        'espectaculos' => 'ESPECTACULO',
        'economía' => 'ECONOMIA',
        'mendoza' => 'PROVINCIALES',
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
            if($state['ok']){
                @$this->setHtmlDomFromString($rss->url, $this->getStreamContext());
                if(!$this->html)return $guardados[$this->codigo][]= "No se pudo acceder al RSS";

                try{
                    if(count($links = $this->getLinksPortada()) > 0){
                        foreach ($links as $link) {
                            if($this->existTitle(trim(strip_tags($link['titulo']))) && !in_array(trim(strip_tags($link['titulo'])),$guardados[$this->codigo])){
                                try{
                                    @$this->setHtmlDomFromString($rss->url . $link['link'], $this->getStreamContext());
                                    if($this->html){
                                        $articulo = $this->articulosTable->newEntity();
                                        $articulo->titulo = trim($link['titulo']);
                                        $articulo->descripcion = $this->getDescripcion('');
                                        $articulo->texto = $this->getContenido();
                                        $articulo->creado = date("Y-m-d H:i:s");
                                        //$articulo->claves = $this->getPalabrasClave();
                                        $articulo->categoria_id = $this->getCategorias(isset($link['seccion'])? $link['seccion'] : '');
                                        $articulo->publicado = $this->verificarIntegridadFechaNoticia($this->getFechaPublicadoHtml(isset($link['seccion'])? $link['seccion'] : ''));
                                        $articulo->portal_id = $rss->portal_id;
                                        $articulo->habilitado = true;
                                        $articulo->url_rss = $link['link'];
                                        $articulo->visitas = 0;

                                        if ($this->articulosTable->save($articulo)) {
                                            if(count($palabras_claves = $this->getPalabrasClave())> 0 ){
                                                $articulo = $this->articulosTable->get($articulo->id);
                                                $articulo->palabras_claves = $palabras_claves;
                                                $this->articulosTable->save($articulo);
                                            }
                                            $imagenes = $this->getImagenes();
                                            foreach($imagenes as $imagen){
                                                $imagen_path = $imagen['path'];
                                                $image_caption = $imagen['descripcion'];
                                                $imagen_name = explode("/", $imagen['path']);
                                                end($imagen_name);
                                                $imagen = $this->saveImagen($imagen_path, $image_caption, current($imagen_name), false);
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
                    else{
                        $guardados[$this->codigo][]= "Error: No se pudo obtener links de articulos";
                    }
                }
                catch(Exception $e){}
            }
            else{
                $guardados[$this->codigo] = $state['state'];
            } 
        }
        return $guardados;
    }

    public function getDescripcion(){
        $descripcion = "";

        $data1 = $this->html->find('p.news-header-sub-title', 0);
        if(!is_null($data1)){
            $descripcion = html_entity_decode($data1->plaintext);
        }

        return $descripcion;
    }

    public function getFechaPublicadoHtml(){
        $fecha = "";

        try{
            $data3 = $this->html->find('.news-header-date', 0);
            //Jueves, 22 de octubre de 2015
            //Wed, 21 Oct 2015 13:09:20 GMT
            $date_arr = ['','','','',''];
            $date_arr[0] = array_key_exists(strtolower(trim($data3->find('span.day',0)->plaintext)),
                    $this->getDaysMap())?$this->getDaysMap()[strtolower(trim($data3->find('span.day',0)->plaintext))]:null;
            $date_arr[1] = trim(explode(' ',$data3->find('span.daynumber',0)->plaintext)[0]);
            $date_arr[3] = array_key_exists(strtolower(trim($data3->find('span.month',0)->plaintext)),
                    $this->getMonthsMap())?$this->getMonthsMap()[strtolower(trim($data3->find('span.month',0)->plaintext))]:null;
            $date_arr[5] = trim(explode(' ',$data3->find('span.year',0)->plaintext)[1]);
            $date_str = $date_arr[0].", ".$date_arr[1]." ".$date_arr[3]." ".$date_arr[5];
            $date_pub = strtotime($date_str." ".date("H:i:s"));
            $fecha = date("Y-m-d H:i:s", strtotime("-50 minutes", $date_pub));

            return $fecha;
        }
        catch(\Exception $e){
            throw new Exception("Error en la obtención de la fecha");
        }
    }

    /*public function getContenido(){
        //$pattern = "/<p[^>]*><\\/p[^>]*>/";
        //$this->clearNode('iframe');
        $this->clearNode('.embed_cont');
        $this->clearNode('script');
        $this->clearNode('noscript');
        //$this->clearNode('blockquote');
        $count_nn = 0;
        $texto = "";
        $data2 = $this->html->find('div.news-paragraph', 0);
        if(!is_null($data2) && count($data2->childNodes()) > 0){
            foreach($data2->childNodes() as $child){
                if($child->tag == 'div' or $child->tag == 'p'){
                    $texto_p = strip_tags($child->innertext); 
                    $texto_p = preg_replace('/\s\s+|&nbsp;/', '', $texto_p);
                    if($texto_p != "" && $texto_p != " "){
                        $texto .= '<p>'.strip_tags($child->innertext,'<b><strong><h3><br>').'</p>';
                    }
                }
                else{
                    $count_nn++;
                }

                if($count_nn > 4 && $texto == ""){
                    throw new Exception("Contenido de noticia irregular");
                }
            }

            if($texto == "" && !is_null($data2->find('body', 0))){
                foreach($data2->childNodes()[0]->childNodes() as $child){
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
                    if($count_nn > 4 && $texto == ""){
                        throw new Exception("Contenido de noticia irregular");
                    }
                }
            }
        }
        else{
            throw new Exception("No hay contenido de noticia");
        }

        return $texto;
    }*/

    public function getContenido(){
        $texto = "";

        $data2 = $this->html->find('.news-paragraph', 0);
        if(!is_null($data2) && !is_null($data2->children)){
            foreach($data2->children as $data){
                //texto sin tag
                if($data->tag == 'br' && !is_null($data2->find('text', 0))){
                    $textos_st = $data2->find('text');
                    foreach($textos_st as $texto_st){
                        $texto_t = strip_tags($texto_st->innertext); 
                        $texto_t = preg_replace('/\s\s+|&nbsp;/', '', $texto_t);
                        if($texto_t != "" && $texto_t != " "){
                            $texto .= "<p>" . strip_tags($texto_st->innertext, '<b><strong><h3><br>') . "</p>#";
                        }
                    }
                    break;
                }
                //imagenes
                elseif($data->tag == "div" && !is_null($data->find('img', 0))){
                    $images_src = $data->find('img');
                    foreach($images_src as $imagen){
                        $texto .= "<img src='" . $imagen->attr['src'] . "' style='display: block; margin: 0 auto;' height='70%'' width='70%'>#";
                    }
                }
                //gifs
                elseif($data->tag == "div" && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "gfycat") !== false){
                    $gif_src = $data->find('iframe', 0);
                    $texto .= "<iframe width='50%' height='50%' allowfullscreen src='" . $gif_src->attr['src'] . "' frameborder='0' scrolling='no' style='position: absolute; top: 0; left: 0;'></iframe>#";
                }
                //videos de liveleak
                elseif($data->tag == "iframe" && strpos($data->attr['src'], "liveleak") !== false){
                    $texto .= "<iframe width='535' height='450' src='" . $data->attr['src'] . "' frameborder='0' allowfullscreen style='display: block; margin: 0 auto;'></iframe>#";
                }
                //videos de facebook
                elseif($data->tag == "div" && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "facebook") !== false && strpos($data->find('iframe', 0)->attr['src'], "video") !== false){
                    // https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2Fmaricel.vidaurreta%2Fvideos%2F10209473590578291%2F&show_text=0&width=560
                    $video_fb_src = $data->find('iframe', 0);
                    $texto .= "<iframe width='535' height='450' src='" . $video_fb_src->attr['src'] . "' frameborder='0' allowfullscreen style='display: block; margin: 0 auto;'></iframe>#";
                }
                //publicacion de facebook
                elseif($data->tag == "div" && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "facebook") !== false){
                    $fb_src = $data->find('iframe', 0);
                    $texto .= "<iframe width='500' height='684' allowtransparency='true' src='" . $fb_src->attr['src'] . "' frameborder='0' scrolling='no' style='display: block; margin: 0 auto; border: none; overflow: hidden'></iframe>#";
                }
                //videos de youtube
                elseif($data->tag == "div" && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "youtu") !== false){
                    // /_post/embed.php?idAdjunto=1322457&width=622&height=351&image=https://youtu.be/zcvAoyPLDs4&tipo=Youtube
                    // https://youtu.be/zcvAoyPLDs4
                    $video_you_src = $data->find('iframe', 0);
                    $video_you_src_link_tmp = explode(':', $video_you_src->attr['src']);
                    $video_you_src_link_tmp = "https:" . end($video_you_src_link_tmp);
                    $video_you_src_link = substr($video_you_src_link_tmp, 0, strpos($video_you_src_link_tmp, '&'));
                    $texto .= "<iframe width='535' height='450' src='" . $video_you_src_link . "' frameborder='0' allowfullscreen style='display: block; margin: 0 auto;'></iframe>#";
                }
                //twitter
                elseif($data->tag == "div" && !is_null($data->find('blockquote', 0)) && strpos($data->find('blockquote', 0)->attr['class'], "twitter") !== false){
                    $twitters_src = $data->find('blockquote');   
                    foreach($twitters_src as $twitter){
                        if(!is_null($twitter->find('a')) && count($twitter->find('a')) == 1 && strpos($twitter->find('a', 0)->attr['href'], "twitter") !== false){
                            $twitter_src_simple = $twitter->find('a', 0);
                            $texto .= "<blockquote align='center' class='twitter-tweet' data-lang='es'><p lang='es' dir='ltr'><a href='" . $twitter_src_simple->attr['href'] . "'></a></p></blockquote><script async src='//platform.twitter.com/widgets.js' charset='utf-8'></script>#";
                        }
                        elseif(!is_null($twitter->find('a')) && count($twitter->find('a')) > 1 && strpos($twitter->find('a', 0)->attr['href'], "twitter") !== false && strpos($twitter->find('a', count($twitter->find('a')) - 1)->attr['href'], "twitter") !== false){
                            $twitter_src_full = $twitter->find('a');
                            $texto .= "<blockquote align='center' class='twitter-tweet' data-lang='en'><p lang='es' dir='ltr'><a href='" . $twitter_src_full[0]->attr['href'] . "'></a></p><a href='" . end($twitter_src_full)->attr['href'] . "'></a></blockquote><script async src='//platform.twitter.com/widgets.js' charset='utf-8'></script>#";
                        }
                        else{
                            $texto .= "";
                        }
                    }
                }
                //instagram
                elseif($data->tag == "div" && !is_null($data->find('blockquote', 0)) && strpos($data->find('blockquote', 0)->attr['class'], "instagram") !== false){
                    $instagram_src = end($data->find('a'));
                    if(strpos($instagram_src->attr['href'], "instagram") !== false){
                        $texto .= "<iframe width='100%' height='762' allowtransparency='true' src='" . $instagram_src->attr['href'] . "' frameborder='0' scrolling='no' data-instgrm-payload-id='instagram-media-payload-0' style='border: 0px; margin: 1px; max-width: 658px; width: calc(100% - 2px); border-radius: 4px; box-shadow: rgba(0, 0, 0, 0.498039) 0px 0px 1px 0px, rgba(0, 0, 0, 0.14902) 0px 1px 10px 0px; display: block; margin: 0 auto; padding: 0px; background: rgb(255, 255, 255);'></iframe>#";
                    }
                }
                //audio de radiocut
                elseif($data->tag == "div" && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "radiocut") !== false){
                    $audio_src = $data->find('iframe', 0);
                    $texto .= "<iframe width='100%' height='65px' allowtransparency='true' src='" . $audio_src->attr['src'] . "' frameborder='no' scrolling='no'></iframe>#";
                }
                //quotes
                elseif($data->tag == "div" && !is_null($data->find('i', 0))){
                    $texto_b = trim($data->find('p', 0));
                    if($texto_b != "" && $texto_b != " "){
                        $texto .= "<blockquote class='style-2'><p><h3>" . strip_tags(preg_replace('#<iframe id="twitter-widget(.*?)</iframe>#', '', $data->plaintext),'<b><strong>') . "</h3></p></blockquote>#";
                    }
                }
                //texto
                elseif($data->tag == 'div' or $data->tag == 'p' or $data->tag == 'br'){
                    $texto_p = strip_tags($data->innertext); 
                    $texto_p = preg_replace('/\s\s+|&nbsp;/', '', $texto_p);
                    if($texto_p != "" && $texto_p != " "){
                        $texto .= "<p>" . strip_tags($data->innertext, '<b><strong><h3><br>') . "</p>#";
                    }
                }
                else{
                    $texto .= "";
                }
            }
        }
        else{
            throw new Exception("La noticia no tiene contenido");
        }

        return $texto;
    }

    /*public function getImagenes(){
        $imagenes = [];
        $data5 =  $this->html->find('.gallery-container',0);
        if(!is_null($data5) && !is_null($data5->find('.itemGallery',0))){
            $img = $data5->find('.itemGallery',0)->find('img',0);            

            if(!is_null($img) && isset($img->attr['src'])){
                $imagenes[] = ["titulo" => null, "path" => $img->attr['src']];
            }

        }
        return $imagenes;
    }*/

    public function getImagenes(){
        $imagenes = [];

        $this->clearNode('.galleryPrevThumb2');
        $this->clearNode('.galleryNextThumb2');
        $data5 = $this->html->find('.snapshot-container', 0);

        if(!is_null($data5)){
            foreach($data5->children as $imagen){
                //imagenes
                if(strpos($imagen->attr['class'] , "image") !== false && !is_null($imagen->find('img', 0)) && isset($imagen->find('img', 0)->attr['src']) && isset($imagen->find('img', 0)->attr['title'])){
                    $image_src = $imagen->find('img', 0);
                    $imagenes[] = [
                        'path' => $image_src->attr['src'],
                        'descripcion' => $image_src->attr['title']
                        ];
                }
                elseif(strpos($imagen->attr['class'] , "image") !== false && !is_null($imagen->find('img', 0)) && isset($imagen->find('img', 0)->attr['src']) && !isset($imagen->find('img', 0)->attr['title'])){
                    $imagenes[] = [
                        'path' => $image_src->attr['src'],
                        'descripcion' => ''
                        ];
                }
                //videos de youtube
                /*elseif(strpos($imagen->attr['class'], "video") !== false && !is_null($imagen->find('iframe', 0)) && isset($imagen->find('iframe', 0)->attr['src']) && strpos($imagen->find('iframe', 0)->attr['src'], "youtube") !== false){
                    $video_src_youtube = $imagen->find('iframe', 0);
                    $imagenes[] = [
                        'path' => $video_src_youtube->attr['src'],
                        'descripcion' => ''
                        ];
                }
                //videos de facebook
                elseif(strpos($imagen->attr['class'], "video") !== false && !is_null($imagen->find('iframe', 0)) && isset($imagen->find('iframe', 0)->attr['src']) && strpos($imagen->find('iframe', 0)->attr['src'], "facebook") !== false){
                    $video_src_facebook = $imagen->find('iframe', 0);
                    $imagenes[] = [
                        'path' => $video_src_facebook->attr['src'],
                        'descripcion' => ''
                        ];
                }*/
            }
        }

        return $imagenes;
    }

    public function getCategorias($seccion){ 
        $codigo = 'EXTRA';

        //es una estructura de noticia seccion normal
        if(empty($seccion)){
            $data4 = $this->html->find('a.seccionNotaFecha',0);
            if(!is_null($data4)){
                $seccion = $data4->plaintext;
                if(array_key_exists(strtolower(trim($seccion)), $this->secciones_diario)){
                    $codigo = $this->secciones_diario[strtolower(trim($seccion))];
                }
            }
            else if(!is_null($this->html->find('div.headerCanales',0))){
                if(!is_null($this->html->find('div.headerCanales',0)->find('a',0))){
                    $seccion = $this->html->find('div.headerCanales',0)->find('a',0)->plaintext;
                    if(array_key_exists(strtolower(trim($seccion)), $this->secciones_diario)){
                        $codigo = $this->secciones_diario[strtolower(trim($seccion))];
                    }
                }
            }
        }
        else{
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
            $this->clearNode('.main-header');
            $this->clearNode('.footer');
            $this->clearNode('.main-nav');
            $this->clearNode('.topic-nav');
            $this->clearNode('.article-ranking');

            //---- Ocasionalmente aparecen destacadas de dos columnas ----//
            $portada_destacadas = $this->html->find('section.widget-highlight',0);
            if(!is_null($portada_destacadas)){                
                foreach($portada_destacadas->find("article") as $article){
                    if(!is_null($article->find('.section-link', 0)) && !is_null($article->find('.title-item', 0))){
                        $links[] = [
                            'titulo'=>html_entity_decode($article->find('.title-item', 0)->find('a',0)->plaintext), 
                            'link'=>$article->find('.title-item', 0)->find('a',0)->href, 
                            'seccion' => $article->find('.section-link', 0)->plaintext
                            ];
                    }
                }
            }
            //---- Fin Bloque inicial 3 notas ----//

            // encuentro un solo articulo aca
            $portada_destacada = $this->html->find('div.region-biggest-item',0);
            if(!is_null($portada_destacada)){
                foreach($portada_destacada->find("article") as $article){
                    if(!is_null($article->find('.section-link', 0)) && !is_null($article->find('.title-item', 0))){
                        $links[] = [
                            'titulo'=>html_entity_decode($article->find('.title-item', 0)->find('a',0)->plaintext), 
                            'link'=>$article->find('.title-item', 0)->find('a',0)->href, 
                            'seccion' => $article->find('.section-link', 0)->plaintext
                            ];
                    }
                }
            }

            // dos bloques con articulos
            $portada_destacada = $this->html->find('div.region-big-items',0);
            if(!is_null($portada_destacada)){
                foreach($portada_destacada->find("article") as $article){
                    if(!is_null($article->find('.section-link', 0)) && !is_null($article->find('.title-item', 0))){
                        $links[] = [
                            'titulo'=>html_entity_decode($article->find('.title-item', 0)->find('a',0)->plaintext), 
                            'link'=>$article->find('.title-item', 0)->find('a',0)->href, 
                            'seccion' => $article->find('.section-link', 0)->plaintext
                            ];
                    }
                }
            }

            // listas de articulos
            $noticias_home_lista = $this->html->find('div.simple-list');
            if(!is_null($noticias_home_lista)){  
                foreach($noticias_home_lista as $lista){
                    foreach($lista->find("article") as $article){
                        if(!is_null($article->find('.title-item', 0))){
                            if(!is_null($article->find('.section-link', 0))){
                                $seccion = $article->find('.section-link', 0)->plaintext;
                            }
                            else{
                                $url_array = explode("/", $article->find('.title-item', 0)->find('a',0)->href);
                                if(isset($url_array[3])){
                                    $seccion = $url_array[3];
                                }
                                else{
                                    continue;
                                }
                            }
                            $links[] = [
                                'titulo'=>html_entity_decode($article->find('.title-item', 0)->find('a',0)->plaintext), 
                                'link'=>$article->find('.title-item', 0)->find('a',0)->href, 
                                'seccion' => $seccion
                                ];
                        }
                    }
                }
            }

            //bloques de 4 notas
            $columnas_notas = $this->html->find('div.four-columns');
            if(!is_null($columnas_notas)){ 
                foreach($columnas_notas as $columna){
                    foreach($columna->find("article") as $article){
                        if(!is_null($article->find('.title-item', 0))){
                            if(!is_null($article->find('.section-link', 0))){
                                $seccion = $article->find('.section-link', 0)->plaintext;
                            }
                            else{
                                $url_array = explode("/", $article->find('.title-item', 0)->find('a',0)->href);
                                if(isset($url_array[3])){
                                    $seccion = $url_array[3];
                                }
                                else{
                                    continue;
                                }
                            }
                            $links[] = [
                                'titulo'=>html_entity_decode($article->find('.title-item', 0)->find('a',0)->plaintext), 
                                'link'=>$article->find('.title-item', 0)->find('a',0)->href, 
                                'seccion' => $seccion
                                ];
                        }
                    }
                }
            }

            $slider_notas = $this->html->find('div.owl-wrapper',0);
            if(!is_null($slider_notas)){
                foreach($slider_notas->find("article") as $article){
                    if(!is_null($article->find('.title-item', 0))){
                        $url_array = explode("/", $article->find('.title-item', 0)->find('a',0)->href);
                        if(isset($url_array[3])){
                            $seccion = $url_array[3];
                        }
                        else{
                            continue;
                        }
                        $links[] = [
                            'titulo'=>html_entity_decode($article->find('.title-item', 0)->find('a',0)->plaintext), 
                            'link'=>$article->find('.title-item', 0)->find('a',0)->href, 
                            'seccion' => $seccion
                            ];
                    }
                }
            }
        }
        catch (\Exception $ex) {}

        return $links;
    }
}
