<?php
namespace App\Controller\Component;

use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;
use \ForceUTF8\Encoding;
use Cake\Controller\Component;
use simple_html_dom;
use Cake\Core\Configure;

/**
 * Description of MendozaPostCrawlerComponent
 *
 * @author Luciano
 */
class MendozaPostCrawlerComponent extends BaseCrawlerComponent{

    public function runCrawler(){
        set_time_limit(0);
        $this->rssesTable = TableRegistry::get('Rsses');
        $this->articulosTable = TableRegistry::get('Articulos');
        $this->imagenesTable = TableRegistry::get('Imagenes');

        $rss = $this->rssesTable->find()
                ->where(['habilitado', 'Portales.codigo' => $this->codigo ])
                ->contain(['Portales'])
                ->first();

        //We add the logic to check if the rss's url response is correct or not
        $url = $rss->url;
        if (!preg_match('/www./', $rss->url)){
            $url = substr_replace($url, "www.", 7, 0);
        }

        $guardados = [$this->codigo =>[]];
        if(!is_null($rss)){
            $state = $this->getStateHeaderXml($url); 
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
                                        $articulo->titulo = html_entity_decode(trim(strip_tags($link['titulo'])));
                                        $articulo->descripcion = $this->getDescripcion();
                                        $articulo->texto = $this->getContenido();
                                        $articulo->creado = date("Y-m-d H:i:s");
                                        $articulo->url_video = $this->getVideo();
                                        $articulo->categoria_id = $this->getCategorias();
                                        $articulo->publicado = $this->verificarIntegridadFechaNoticia($this->getFechaPublicado());
                                        $articulo->portal_id = $rss->portal_id;
                                        $articulo->habilitado = true;
                                        $articulo->url_rss = $rss->url.$link['link'];
                                        $articulo->visitas = 0;

                                        if ($this->articulosTable->save($articulo)) {
                                            $imagenes = $this->getImagenes();

                                            foreach($imagenes as $imagen){
                                                $imagen_path = $imagen['path'];
                                                $image_caption = $imagen['descripcion'];

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
                $guardados[$this->codigo]=$state['state'];
            }
        }
        return $guardados;
    }

    public function getDescripcion(){
        $descripcion = '';

        $data1 = $this->html->find('.description-boxed', 0);
        if(!is_null($data1) && !is_null($data1->plaintext)){
            $descripcion = $data1->plaintext;
        }

        return $descripcion;
    }

    public function getFechaPublicado(){
        $fecha = '';

        try{
            $data3 = $this->html->find('.date-ddmmyy', 0);
            $data31 = $this->html->find('.date-relative', 0);
            if(!is_null($data3) && !is_null($data31)){
                $fecha_noticia = $this->getMes($data3->innertext);                
                $fecha_noticia = explode("-", $fecha_noticia);
                $fecha_actual = new \DateTime();
                $fecha_actual->setDate(reset($fecha_noticia), $fecha_noticia[1], end($fecha_noticia));
                $fecha = date('Y-m-d H:i:s', strtotime($this->getTiempo($data31->innertext), strtotime($fecha_actual->format('Y-m-d H:i:s'))));
            }
        }
        catch(Exception $e){
            throw new Exception("Error en fechas");
        }

        return $fecha;
    }

    public function getTiempo($relativo){
        $tiempo = '';

        if(!is_null($relativo)){
            $tiempo_relativo = explode(" ", trim($relativo));
            unset($tiempo_relativo[count($tiempo_relativo) - 1]);
            if(end($tiempo_relativo) == 'día'){
                $unidad = " day";
            }
            elseif(end($tiempo_relativo) == 'días'){
                $unidad = " days";
            }
            elseif(end($tiempo_relativo) == 'hora'){
                $unidad = " hour";
            }
            elseif(end($tiempo_relativo) == 'horas'){
                $unidad = " hours";
            }
            elseif(end($tiempo_relativo) == 'minuto'){
                $unidad = " minute";
            }
            else{
                $unidad = " minutes";
            }
            $tiempo = "-" . reset($tiempo_relativo) . $unidad;
        }

        return $tiempo;
    }
    
    public function getMes($data){
        $fecha_final = '';

        if(!is_null($data)){
            $months = ['Ene' => '01', 'Feb' => '02', 'Mar' => '03', 'Abr' => '04', 'May' => '05', 
                'Jun' => '06', 'Jul' => '07', 'Ago' => '08', 'Sep' => '09', 'Oct' => '10', 
                'Nov' => '11', 'Dic' => '12'];
            $fecha = explode(" ", trim($data));
            $month = $fecha[1];
            if(array_key_exists(trim($month), $months)){
                $month = $months[trim($month)];
            }
            $fecha_final = end($fecha) . "-" . $month . "-" . reset($fecha);
        }

        return $fecha_final;
    }

    public function getContenido(){
        $texto = "";

        $data2 = $this->html->find('.vsmcontent', 0);
        if(!is_null($data2) && !is_null($data2->children)){
            foreach($data2->children as $data_){
                //quotes
                if($data_->tag == "p" && isset($data_->attr['class']) && strpos($data_->attr['class'], "quote") !== false){
                    $texto_q = trim($data_->plaintext);
                    if($texto_q != "" && $texto_q != " "){
                        $texto .= "<blockquote class='style-2'><p><h3>" . strip_tags(preg_replace('#<iframe id="twitter-widget(.*?)</iframe>#', '', $texto_q),'<b><strong>') . "</h3></p></blockquote>#";
                    }
                }
                //imagenes
                elseif(!is_null($data_->find('img', 0)) && strpos($data_->find('img', 0)->attr['src'], "image") !== false){
                    //obtenemos la url de la foto
                    $image_src = $data_->find('img', 0);
                    $image_src_full = "http://www.mendozapost.com" . $image_src->attr['src'];
                    $texto .= "<img src='" . $image_src_full . "' style='display: block; margin: 0 auto;' height='70%' width='70%'>#";
                    //obtenemos el caption de la foto
                    if(!is_null($data_->find('figcaption', 0)) && !is_null($data_->find('text', 0))){
                        $image_caption = $data_->find('text', 0);
                        $texto .= '<p align="center">' . '<i>' . $image_caption->innertext . '</i>' . '</p>#';
                    }
                }
                //videos de Facebook
                elseif($data_->tag == "figure" && strpos($data_->attr['class'], "widget-type-iframe") !== false && !is_null($data_->find('iframe', 0)) && strpos($data_->find('iframe', 0)->attr['src'], "facebook") !== false && strpos($data_->find('iframe', 0)->attr['src'], "videos") !== false){
                    $video_src_fb = $data_->find('iframe', 0);
                    $width_fb = $video_src_fb->attr['width'];
                    $height_fb = $video_src_fb->attr['height'];
                    $texto .= "<iframe width='" . $width_fb . "' height='" . $height_fb . "' src='" . $video_src_fb->attr['src'] . "' frameborder='0' allowfullscreen style='display: block; margin: 0 auto;'></iframe>#";
                }
                //videos de Youtube
                elseif($data_->tag == "figure" && strpos($data_->attr['class'], "widget-type-YOUTUBE") !== false && !is_null($data_->find('iframe', 0)) && strpos($data_->find('iframe', 0)->attr['src'], "youtube") !== false){
                    $video_src_you = $data_->find('iframe', 0);
                    $height_you = $video_src_you->attr['height'];
                    $width_you = $video_src_you->attr['width'];
                    if($width_you > 900){
                        $height_you = 480;
                        $width_you = (480*$video_src_you->attr['width'])/$video_src_you->attr['height'];
                    }
                    $texto .= "<iframe width='" . $width_you . "' height='" . $height_you . "' src='" . $video_src_you->attr['src'] . "' frameborder='0' allowfullscreen style='display: block; margin: 0 auto;'></iframe>#";
                }
                //videos de Vine
                elseif($data_->tag == "figure" && strpos($data_->attr['class'], "widget-type-vine") !== false && !is_null($data_->find('iframe', 0)) && strpos($data_->find('iframe', 0)->attr['src'], "vine") !== false){
                    $video_src_vine = $data_->find('iframe', 0);
                    $width_vine = $video_src_vine->attr['width'];
                    $height_vine = $video_src_vine->attr['height'];
                    $texto .= "<iframe width='" . $width_vine . "' height='" . $height_vine . "' src='" . $video_src_vine->attr['src'] . "' frameborder='0' allowfullscreen style='display: block; margin: 0 auto;'></iframe>#";
                }
                //estado de Facebook
                elseif($data_->tag == "figure" && strpos($data_->attr['class'], "widget-type-iframe") !== false && !is_null($data_->find('iframe', 0)) && strpos($data_->find('iframe', 0)->attr['src'], "facebook") !== false){
                    $fb_src = $data_->find('iframe', 0);
                    $texto .= "<iframe width='500' height='684' allowtransparency='true' src='" . $fb_src->attr['src'] . "' frameborder='0' scrolling='no' style='display: block; margin: 0 auto; border: none; overflow: hidden'></iframe>#";
                }
                //estado de Twitter
                elseif($data_->tag == "figure" && strpos($data_->attr['class'], "widget-type-twitter") !== false && !is_null($twitter = $data_->find('blockquote', 0)) && strpos($twitter->attr['class'], "twitter-tweet") !== false && count($twitter->find('a')) > 0){
                    $twitter_src = [];
                    $twitter_srcs_foto = $twitter->find('a');
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
                //video de Twitter
                elseif($data_->tag == "figure" && isset($data_->attr['class']) && strpos($data_->attr['class'], "vsmwidget fixed") !== false && !is_null($data_->find('blockquote', 0)) && strpos($data_->find('blockquote', 0)->attr['class'], "twitter-video") !== false){
                    $twitters_video_src = [];
                    $twitter_srcs_video = $data_->find('a');
                    foreach($twitter_srcs_video as $twitter_src_video){
                        if(strpos($twitter_src_video->attr['href'], "t.co") !== false || strpos($twitter_src_video->attr['href'], "status") !== false){
                            array_push($twitters_video_src, $twitter_src_video);
                        }
                    }
                    $texto .= "<blockquote align='center' class='twitter-tweet' data-lang='en'><p lang='es' dir='ltr'><a href='" . $twitters_video_src[0]->attr['href'] . "'></a></p><a href='" . end($twitters_video_src)->attr['href'] . "'></a></blockquote><script async src='//platform.twitter.com/widgets.js' charset='utf-8'></script>#";
                }
                //timeline de Twitter
                elseif($data_->tag == "figure" && isset($data_->attr['class']) && strpos($data_->attr['class'], "vsmwidget fixed") !== false && !is_null($data_->find('a', 0)) && strpos($data_->find('a', 0)->attr['class'], "twitter-timeline") !== false){
                    //https://twitter.com/Interior/status/753955294321999873
                    //https://twitter.com/search?q=%23GH2016
                    $twitter_timeline = $data_->find('a', 0);
                    if(strpos($twitter_timeline->attr['href'], "hashtag") !== false){
                        $texto .= "<center><a class='twitter-timeline' href='" . $twitter_timeline->attr['href'] . "' data-widget-id='" . $twitter_timeline->attr['data-widget-id'] . "'></a></center><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','twitter-wjs');</script>#";
                    }
                    else{
                        $texto .= "<center><a class='twitter-timeline' href='" . $twitter_timeline->attr['href'] . "'></a></center><script async src='//platform.twitter.com/widgets.js' charset='utf-8'></script>#";
                    }
                }
                //estado de Instagram
                elseif($data_->tag == "figure" && strpos($data_->attr['class'], "widget-type-instagram") !== false){
                    $instagram_src = $data_->find('a', 0);
                    $texto .= "<blockquote class='instagram-media' data-instgrm-captioned data-instgrm-version='7' style=' background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 0px auto; max-width:658px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);'><div style='padding:8px;'> <div style=' background:#F8F8F8; line-height:0; margin-top:40px; padding:55.6954436451% 0; text-align:center; width:100%;'></div><p style=' margin:8px 0 0 0; padding:0 4px;'><a href='" . $instagram_src->attr['href'] . "' style=' color:#000; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none; word-wrap:break-word;' target='_blank'></a></p><p style=' color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;'></p></div></blockquote><script async defer src='//platform.instagram.com/en_US/embeds.js'></script>#<br/>";
                }
                //audio de SounCloud
                elseif($data_->tag == "figure" && strpos($data_->attr['class'], "widget-type-iframe") !== false && !is_null($data_->find('iframe', 0)) && strpos($data_->find('iframe', 0)->attr['src'], "soundcloud") !== false){
                    $audio_src = $data_->find('iframe', 0);
                    $texto .= "<iframe width='100%' height='166' allowtransparency='true' src='" . $audio_src->attr['src'] . "' frameborder='no' scrolling='no'></iframe>#";
                }
                //timeline de Storify
                elseif($data_->tag == "figure" && strpos($data_->attr['class'], "widget-type-iframe") !== false && !is_null($data_->find('iframe', 0)) && strpos($data_->find('iframe', 0)->attr['src'], "storify") !== false){
                    $storify_src = $data_->find('iframe', 0);
                    $texto .= "<iframe src='" . $storify_src->attr['src'] . "' frameborder='no' allowtransparency='true' scrolling='yes' style='display: block; margin: 0 auto; border: solid #f2f2f2 4px; border-radius: 10px; overflow: hidden; width: 810px; height: 800px; background-color: transparent;'></iframe>#";
                }
                //Google Maps
                elseif($data_->tag == "figure" && strpos($data_->attr['class'], "widget-type-googlemaps") !== false && !is_null($data_->find('iframe', 0)) && strpos($data_->find('iframe', 0)->attr['src'], "google") !== false && strpos($data_->find('iframe', 0)->attr['src'], "maps") !== false){
                    $gmaps_src = $data_->find('iframe', 0);
                    $width_gmaps = $gmaps_src->attr['width'];
                    $height_gmaps = $gmaps_src->attr['height'];
                    $texto .= "<iframe width='" . $width_gmaps . "' height='" . $height_gmaps . "' src='" . $gmaps_src->attr['src'] . "' frameborder='0' style='display:block; margin: 0 auto; border:0' allowfullscreen=''></iframe>#";
                }
                //si es texto plano
                elseif($data_->tag == "p" && !is_null($data_->find('text', 0)) && !is_null($data_->find('text', 0)->innertext)){
                    $texto_p = strip_tags($data_->innertext, '<b><strong>'); 
                    $texto_p = preg_replace('/\s\s+|&nbsp;/', '', $texto_p);
                    if($texto_p != "" && $texto_p != " " && strpos($texto_p, "para Mendoza Post") !== true  && strpos($texto_p, "Ver:") !== true && strpos($texto_p, "Para leer la nota completa") !== true &&  strpos($texto_p, "Especial para Mendoza Post") !== true){
                        $texto .= '<p>' . strip_tags($data_->innertext, '<b><strong>') . '</p>#';
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
        $texto = Encoding::fixUTF8($texto);

        return $texto;
    }

    public function getImagenes(){
        $imagenes = [];

        $data4 =  $this->html->find('meta[property="og:image"]', 0);
        if(!is_null($data4) && isset($data4->attr['content'])){
            $imagenes[] = [
                'path' => trim($data4->attr['content']),
                'descripcion' => ''
                ];
        }

        return $imagenes;
    }

    public function getCategorias(){
        $codigo = 'EXTRA';

        $seccion =  $this->html->find('#content-wrapper', 0);
        if(!is_null($seccion->attr['class'])){
            switch($seccion){
                case(strpos($seccion->attr['class'], 'nacionales') !== false):
                    $codigo = 'NACIONALES';
                    break;
                case(strpos($seccion->attr['class'], 'internacionales') !== false):
                    $codigo = 'INTERNACIONALES';
                    break;
                case(strpos($seccion->attr['class'], 'la-provincia') !== false):
                    $codigo = 'PROVINCIALES';
                    break;
                case(strpos($seccion->attr['class'], 'sociales') !== false):
                    $codigo = 'SOCIALES';
                    break;
                case(strpos($seccion->attr['class'], 'sociedad') !== false):
                    $codigo = 'SOCIEDAD';
                    break;
                case(strpos($seccion->attr['class'], 'deportes') !== false):
                    $codigo = 'DEPORTES';
                    break;
                case(strpos($seccion->attr['class'], 'politica') !== false):
                    $codigo = 'POLITICA';
                    break;
                case(strpos($seccion->attr['class'], 'economia') !== false):
                    $codigo = 'ECONOMIA';
                    break;
                case(strpos($seccion->attr['class'], 'espectaculo') !== false):
                    $codigo = 'ESPECTACULO';
                    break;
                case(strpos($seccion->attr['class'], 'tecnologia') !== false):
                    $codigo = 'TECNOLOGIA';
                    break;
                case(strpos($seccion->attr['class'], 'policiales') !== false):
                    $codigo = 'POLICIALES';
                    break;
                case(strpos($seccion->attr['class'], 'weddings') !== false):
                    $codigo = 'WEDDINGS';
                    break;
            }
        }

        $categoria_id = TableRegistry::get('Categorias')
                        ->find()
                        ->where(['Categorias.codigo' => $codigo])
                        ->first()
                        ->id;

        return $categoria_id;
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

    public function getVideo(){
        $url_video = "";

        $data4 = $this->html->find('.headnews-item', 0);
        if(!is_null($data4) && !is_null($data4->find('iframe', 0))){
            $video_src = $data4->find('iframe', 0);
            if(!is_null($video_src) && !is_null($video_src->attr['src'])){
                $video_src_height = $video_src->attr['height'];
                $video_src_width = $video_src->attr['width'];
                if($video_src_width > 900){
                    $video_src_height = 480;
                    $video_src_width = (480*$video_src->attr['width'])/$video_src->attr['height'];
                }
                $url_video .= "<iframe width='" . $video_src_width . "' height='" . $video_src_height . "' src='" . $video_src->attr['src'] . "' frameborder='0' allowfullscreen style='display: block; margin: 0 auto;'></iframe>&nbsp;";
            }
        }

        return $url_video;
    }
}