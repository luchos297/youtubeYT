<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use Exception;
/**
 * Description of LaNacionCrawlerComponent
 *
 * @author Jesús
 */
class LaNacionCrawlerComponent extends BaseCrawlerComponent{

    private $secciones_diario = [
        'política' => 'POLITICA',
        'el mundo' => 'INTERNACIONALES',
        'ideas' => 'SOCIEDAD',
        'turismo' => 'SOCIEDAD',
        'seguridad' => 'NACIONALES',
        'sociedad' => 'SOCIEDAD',
        'buenos aires' => 'NACIONALES',
        'nacionales' => 'NACIONALES',
        'tecnología' => 'TECNOLOGIA',
        'canal espectáculos' => 'ESPECTACULO',
        'economía' => 'ECONOMIA',
        'deportiva' => 'DEPORTES'
        ];

    public function runCrawler(){
        set_time_limit(0);
        $this->rssesTable = TableRegistry::get('Rsses');
        $this->articulosTable = TableRegistry::get('Articulos');

        $rss = $this->rssesTable->find()
                ->where(['habilitado', 'Portales.codigo' => $this->codigo ])
                ->contain(['Portales'])
                ->first();

        $guardados = [$this->codigo =>[]];
        if(!is_null($rss)){            
            $context = $this->getStreamContext();
            try{
                @$content_xml = file_get_contents($rss->url);
                if (isset($content_xml)) {   
                    $noticias = @simplexml_load_string($content_xml);
                    if(!$noticias)return $guardados[$this->codigo][] = "No se pudo acceder al rss";
                    foreach ($noticias->entry as $noticia) {
                        if($this->existTitle((string)$noticia->title) && !in_array(trim($noticia->title),$guardados[$this->codigo])){
                            try{
                                $this->setHtmlDomFromString((string)$noticia->link['href'], $context);
                                if($this->html){
                                    $this->clearNode('script');
                                    $articulo = $this->articulosTable->association('Imagenes')->newEntity();
                                    $articulo->publicado = $this->verificarIntegridadFechaNoticia($this->getFechaPublicadoRss((string)$noticia->updated));
                                    $articulo->titulo = trim((string)$noticia->title);
                                    $articulo->descripcion = trim((string)$noticia->content->div);
                                    $articulo->categoria_id = $this->getCategorias();
                                    $articulo->texto = $this->getContenido();
                                    $articulo->url_video = $this->getVideo();
                                    $articulo->creado = date("Y-m-d H:i:s");
                                    $articulo->portal_id = $rss->portal_id;
                                    $articulo->habilitado = true;
                                    $articulo->url_rss = trim((string)$noticia->link['href']);
                                    $articulo->visitas = 0;
                                    if ($this->articulosTable->save($articulo)) {
                                        $imagenes = $this->getImagenes();
                                        foreach($imagenes as $imagen){
                                            $imagen_path = $imagen['path'];
                                            $imagen_name = explode("/", $imagen['path']);
                                            end($imagen_name);
                                            $imagen = $this->saveImagen($imagen_path, '', current($imagen_name),true);
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
            catch (Exception $e){
                $guardados[$this->codigo][]= "No se pudo acceder al rss";
            }
        }
        return $guardados;
    }

    public function getContenido(){
        $texto = "";

        $this->clearNode('.banner');
        $this->clearNode('.relacionadas');
        $this->clearNode('.mas-sobre-tema');
        $this->clearNode('ul');
        $this->clearNode('#herramientas-sociales');

        $data1 =  $this->html->getElementById('cuerpo', 0);
        if(!is_null($data1->children)){
            $data1_children = $data1->children;
            //removemos el primer elemento que es un estilo
            unset($data1_children[0]);
            $data1_children = array_values($data1_children);
            //si la imagen del primer elemento es igual que la de la noticia, la removemos del arreglo del contenido
            $data1_children1_body = $data1->find('.primer-parrafo', 0);
            if(!is_null($data1_children1_body)){
                $data1_children1_body_figure = $data1_children1_body->find('img', 0);
                if(!is_null($data1_children1_body_figure)){
                    unset($data1_children[0]);
                    $data1_children = array_values($data1_children);
                }
            }
            foreach($data1_children as $data){
                //imagenes
                if($data->tag == 'figure' && !is_null($data->find('img', 0)) && !is_null($data->find('img', 0)->attr['src'])){
                    $image_src = $data->find('img', 0);
                    $texto .= "<img src='" . $image_src->attr['src'] . "' style='display: block; margin: 0 auto;' height='70%' width='70%'>#";
                    if(!is_null($data->find('figcaption', 0))){
                        $caption_src = $data->find('figcaption', 0)->find('text', 0);
                        $texto .= '<p align="center"><i>' . $caption_src->plaintext . '</i></p>#';
                    }
                }
                //imagenes de Gfycat
                elseif($data->tag == 'div' && isset($data->attr['class']) && strpos($data->attr['class'], "video") !== false && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "gfycat") !== false){
                    $gfycat_src = $data->find('iframe', 0);
                    $texto .= "<iframe width='" . $gfycat_src->attr['width'] . "' height='" . $gfycat_src->attr['height'] . "' src='" . $gfycat_src->attr['src'] . "' frameborder='0' scrolling='no' style='display: block; margin: 0 auto;'></iframe>&nbsp;#";
                }
                //videos de Youtube
                elseif($data->tag == 'div' && isset($data->attr['class']) && strpos($data->attr['class'], "video") !== false && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "youtube") !== false){
                    $video_src_you = $data->find('iframe', 0);
                    $height_you = $video_src_you->attr['height'];
                    $width_you = $video_src_you->attr['width'];
                    if($width_you > 900){
                        $height_you = 480;
                        $width_you = (480*$video_src_you->attr['width'])/$video_src_you->attr['height'];
                    }
                    $texto .= "<iframe width='" . $width_you . "' height='" . $height_you . "' src='" . $video_src_you->attr['src'] . "' frameborder='0' allowfullscreen style='display: block; margin: 0 auto;'></iframe>#";
                }
                //videos en aside
                elseif($data->tag == 'p' && !is_null($data->find('aside', 0)) && strpos($data->find('aside', 0)->attr['class'], "video") !== false){
                    $video_src_aside = $data->find('aside', 0);
                    $url_json = 'http://content.jwplatform.com/feeds/' . $video_src_aside->attr['data-jwkey'] . '.json';
                    $url_video_json = $this->getVideoURLJWPlayer($url_json);
                    if(!is_null($url_video_json)){
                        unset(reset($url_video_json->playlist)->sources[0]);
                        foreach(reset($url_video_json->playlist)->sources as $video){
                            if($video->type == "video/mp4" && strpos($video->label, "360p") !== false){
                                $texto .= "<video width='640' height='480' src='" . $video->file . "' style='display: block; margin: 0 auto; background-color: black;' controls></video>#";
                            }
                        }
                        $texto .= '<p align="center"><i>' . reset($url_video_json->playlist)->title . '</i></p>#';
                    }
                }
                //estado de FB
                elseif($data->tag == 'div' && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "facebook") !== false && strpos($data->find('iframe', 0)->attr['src'], "post") !== false){
                    $fb_src = $data->find('iframe', 0);
                    $width_fb = $fb_src->attr['width'];
                    $height_fb = $fb_src->attr['height'];
                    $texto .= "<iframe width='" . $width_fb . "' height='" . $height_fb . "' allowtransparency='true' src='" . $fb_src->attr['src'] . "' frameborder='0' scrolling='no' style='display: block; margin: 0 auto; border: none; overflow: hidden'></iframe>#";
                }
                //video de FB
                elseif($data->tag == 'div' && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "facebook") !== false && strpos($data->find('iframe', 0)->attr['src'], "video") !== false){
                    $video_src_fb = $data->find('iframe', 0);
                    $texto .= "<iframe width='50%' height='335' src='" . $video_src_fb->attr['src'] . "' frameborder='0' allowfullscreen style='display: block; margin: 0 auto;'></iframe>#";
                }
                //estado de Twitter
                elseif($data->tag == "div" && !is_null($twitter = $data->find('blockquote', 0)) && strpos($twitter->attr['class'], "twitter-tweet") !== false && count($twitter->find('a')) > 0){
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
                elseif($data->tag == "div" && !is_null($data->find('blockquote', 0)) && strpos($data->find('blockquote', 0)->attr['class'], "twitter-video") !== false){
                    $twitters_video_src = [];
                    $twitter_srcs_video = $data->find('a');
                    foreach($twitter_srcs_video as $twitter_src_video){
                        if(strpos($twitter_src_video->attr['href'], "t.co") !== false || strpos($twitter_src_video->attr['href'], "status") !== false){
                            array_push($twitters_video_src, $twitter_src_video);
                        }
                    }
                    $texto .= "<blockquote align='center' class='twitter-tweet' data-lang='en'><p lang='es' dir='ltr'><a href='" . $twitters_video_src[0]->attr['href'] . "'></a></p><a href='" . end($twitters_video_src)->attr['href'] . "'></a></blockquote><script async src='//platform.twitter.com/widgets.js' charset='utf-8'></script>#";
                }
                //timeline de Twitter
                elseif($data->tag == "div" && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "twitter") !== false){
                    $twitter_timeline = $data->find('iframe', 0);
                    $width_tw = $twitter_timeline->attr['width'];
                    $height_tw = $twitter_timeline->attr['height'];
                    $texto .= "<iframe frameborder='0' width='" . $width_tw . "' height='" . $height_tw . "' scrolling='auto' src='" . $twitter_timeline->attr['src'] . "' style='display: block; margin: 0 auto;'></iframe>#";
                }
                //timeline de Storify
                elseif($data->tag == "div" && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "storify") !== false){
                    $storify_src = $data->find('iframe', 0);
                    $texto .= "<iframe src='" . $storify_src->attr['src'] . "' frameborder='no' allowtransparency='true' scrolling='yes' style='display: block; margin: 0 auto; border: solid #f2f2f2 4px; border-radius: 10px; overflow: hidden; width: 810px; height: 800px; background-color: transparent;'></iframe>#";
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
                //tablas
                elseif($data->tag == "div" && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "spreadsheet") !== false){
                    $tabla_src = $data->find('iframe', 0);
                    $width_tabla = $tabla_src->attr['width'];
                    $height_tabla = $tabla_src->attr['height'];
                    $texto .= "<iframe class='ifm no-responsive' width='" . $width_tabla . "' height='" . $height_tabla . "' src='" . $tabla_src->attr['src'] . "' scrolling'yes' frameborder='0' style='display: block; margin: 0 auto;'>&nbsp;</iframe>#";
                }
                //tags H
                elseif($data->tag == 'h2'){
                    $texto_h2 = strip_tags($data->innertext,'<b><strong><h2>'); 
                    if($texto_h2 != "" && $texto_h2 != " "){
                        $texto .= '<h6>'.strip_tags($data->innertext,'<b><strong><h6>').'</h2>#';
                    }
                }
                //texto plano
                elseif($data->tag == 'p'){
                    if($data->outertext != "" && $data->outertext != " " && strpos($data->outertext, "jpb") !== true){
                        $texto .= $data->outertext;
                    }
                }
                else{
                    $texto .= "";
                }
            }
        }

        return $texto;
    }

    public function getVideoURLJWPlayer($url){
        $screenshot_url_json = "";

        if(!is_null($url)){
            //creamos un nuevo cURL resource
            $ch = curl_init();
            //seteamos las opciones
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);  // No HTTP headers
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // return the data
            //ejecutamos el post
            $resultset = curl_exec($ch);
            //cerramos la coneccion
            curl_close($ch);
            //obtenemos la URL de la imagen
            $screenshot_url_json = json_decode($resultset);
        }

        return $screenshot_url_json;
    }

    public function getImagenes(){
        $imagenes = [];

        $data2_galeria = $this->html->find('.galeria', 0);
        if(!is_null($data2_galeria)){
            $data2_galeria = $data2_galeria->find(".slideshow", 0);
            if(!is_null($data2_galeria) && !is_null($data2_galeria->children)){
                foreach($data2_galeria->children as $picture){
                    $img_src = $picture->find('img', 0);
                    if(!is_null($img_src)){
                        $imagenes[] = [
                            'path' => trim($img_src->attr['src']),
                            'descripcion' => trim($img_src->attr['alt'])
                            ];
                    }
                }
            }
        }

        $data2 = $this->html->find('meta[property="og:image"]', 0);
        if(!is_null($data2) && isset($data2->attr['content'])){   
            $imagenes[] = [
                'path' => trim($data2->attr['content']),
                'descripcion' => ''
                ];
        }

        return $imagenes; 
    }

    public function getFechaPublicadoRss($date_string){
        $date_pub = strtotime($date_string);
        return date("Y-m-d H:i:s", $date_pub);
    }

    public function getCategorias(){ 
        $codigo = 'EXTRA';

        $data5 = $this->html->find(".breadcrumb", 0);
        if(!is_null($data5) && !is_null($data5->children)){
            if(count($data5->children) > 2){
                $data5 = $data5->children[count($data5->children) - 2];
            }
            else{
                $data5 = $data5->children[count($data5->children) - 1];
            }
            $seccion = $data5->find('a', 0)->plaintext;
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

    public function getVideo(){
        $url_video = "";

        $data4 = $this->html->find('#nota', 0);
        if(!is_null($data4)){
            $video_src_aside = $data4->find('aside', 0);
            if(!is_null($video_src_aside)){
                $url_json = 'http://content.jwplatform.com/feeds/' . $video_src_aside->attr['data-jwkey'] . '.json';
                $url_video_json = $this->getVideoURLJWPlayer($url_json);
                if(!is_null($url_video_json)){
                    unset(reset($url_video_json->playlist)->sources[0]);
                    foreach(reset($url_video_json->playlist)->sources as $video){
                        if($video->type == "video/mp4" && strpos($video->label, "360p") !== false){
                            $url_video = "<video width='640' height='480' src='" . $video->file . "' style='display: block; margin: 0 auto; background-color: black;' controls></video>&nbsp;";
                        }
                    }
                }
            }
        }

        return $url_video;
    }

    public function testNoticia(){
        set_time_limit(0);

        $state = $this->getStateHeaderXml("http://www.lanacion.com.ar/"); 
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
                $articulo->url_video = $this->getVideo();
                $articulo->publicado = date("Y-m-d H:i:s");
                $articulo->portal_id = 6;
                $articulo->habilitado = true;
                $articulo->url_rss = html_entity_decode(trim($url));
                $articulo->visitas = 0;
                TableRegistry::get('Articulos')->save($articulo);
            }
        }
    }
}
