<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use simple_html_dom;

/**
 * Description of MDZCrawlerComponent
 *
 * @author Jesús
 */
class MDZCrawlerComponent extends BaseCrawlerComponent {

    private $secciones_diario = [
        'elecciones 2015' => 'POLITICA',
        'Politica' => 'POLITICA',
        'BBC Mundo' => 'INTERNACIONALES',
        'Mundo' => 'INTERNACIONALES',
        'Papa Francisco' => 'INTERNACIONALES',
        'Policiales' => 'POLICIALES',
        'Deportes' => 'DEPORTES',
        'Dinero' => 'ECONOMIA',
        'Sociales' => 'SOCIALES',
        'Sociedad' => 'SOCIEDAD',
        'Mediáticos' => 'ESPECTACULO',
        'Espectáculos' => 'ESPECTACULO'
        ];

    private $excluye_titulos = [
        'Hagamos lío'
    ];

    private $excluye_seccion = ['opinión'];

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
            $context = $this->getStreamContext();
            try {
                $content_xml = @file_get_contents($rss->url, false, $context);
                if (isset($content_xml)) {
                    $noticias = simplexml_load_string($content_xml);
                    if(!$noticias)return $guardados[$this->codigo][]= "No se pudo acceder al rss";
                    foreach ($noticias->channel->item as $noticia) {
                        if($this->existTitle((string)$noticia->title) && !in_array(trim($noticia->title),$guardados[$this->codigo])){
                            try{
                                @$this->setHtmlDomFromUrl($noticia->link);
                                if($this->html){
                                    //noticias rechazadas
                                    if(!array_key_exists(trim((string)$noticia->title), $this->excluye_titulos)){
                                        $articulo = $this->articulosTable->newEntity();
                                        $articulo->publicado = $this->verificarIntegridadFechaNoticia($this->getFechaPublicadoRss((string)$noticia->pubDate));
                                        $articulo->titulo = trim((string)$noticia->title);
                                        $articulo->descripcion = trim((string)$noticia->description);
                                        $articulo->texto = $this->getContenido();
                                        //$articulo->palabras_claves = trim((string)$noticia->title);
                                        $articulo->creado = date("Y-m-d H:i:s");
                                        $articulo->url_video = $this->getVideo();
                                        $articulo->categoria_id = $this->getCategorias();
                                        $articulo->portal_id = $rss->portal_id;
                                        $articulo->habilitado = true;
                                        $articulo->url_rss = trim((string)$noticia->link);
                                        $articulo->visitas = 0;

                                        if($this->articulosTable->save($articulo)) {
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
                                                $imagen = $this->saveImagen($imagen_path, $image_caption, current($imagen_name),false);
                                                if($imagen != null){
                                                    $this->getConnection()->insert('articulo_imagen', ['articulo_id' => $articulo->id,'imagen_id' => $imagen->id]);
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
            catch (Exception $e){
                $guardados[$this->codigo][]= "No se pudo acceder al rss";
            }
        }
        return $guardados;
    }

    public function getContenido(){
        $texto = "";
        $data2 = $this->html->find('#vsmcontent', 0);

        if(!is_null($data2)){
            foreach($data2->children as $data){
                if($data->tag == "p" && is_null($data->find('a', 0)) && is_null($data->find('time', 0)) && !isset($data->attr['lang']) || $data->tag == "ul"){
                    $texto_p = strip_tags($data->innertext,'<b><strong>'); 
                    $texto_p = preg_replace('/\s\s+|&nbsp;/', '', $texto_p);
                    if($texto_p != "" && $texto_p != " "){
                        $texto .= '<p>'.strip_tags(preg_replace('#<iframe id="twitter-widget(.*?)</iframe>#','',$data->innertext),'<b><strong>').'</p>#';
                    }
                }
                elseif($data->tag == "h2"){
                    $texto_h2 = strip_tags($data->innertext,'<b><strong>'); 
                    $texto_h2 = preg_replace('/\s\s+|&nbsp;/', '', $texto_h2);
                    if($texto_h2 != "" && $texto_h2 != " "){
                        $texto .= "<blockquote class='style-2'><p>'" . strip_tags(preg_replace('#<iframe id="twitter-widget(.*?)</iframe>#','',$data->innertext),'<b><strong>') . "'</p></blockquote>#";
                    }
                }
                //imagen con source property
                elseif($data->tag == "figure" && !is_null($data->find('source', 0)) && !is_null($data->find('source', 0)->attr['srcset'])){
                    $image_src = $data->find('source', 0);
                    $image_src_full = "http://i1.mdzol.com" . substr($image_src->attr['srcset'], 0, strpos($image_src->attr['srcset'], '?'));
                    $texto .= "<img src='" . $image_src_full . "' style='display: block; margin: 0 auto;' height='70%'' width='70%'>#"; 
                }
                //imagen con img property
                /*elseif($data->tag == "figure" && !is_null($data->find('img', 0)) && !is_null($data->find('img', 0)->attr['src'])){
                    $image_src = $data->find('img', 0);
                    $image_src_full = substr($image_src->attr['src'], 0, strpos($image_src->attr['src'], '?'));
                    $texto .= "<img src='" . $image_src_full . "' style='display: block; margin: 0 auto;' height='70%'' width='70%'>#";
                }*/
                //twitter con el texto y foto
                elseif($data->tag == "figure" && !is_null($twitter = $data->find('blockquote', 0)) && strpos($twitter->attr['class'], "twitter") !== false && count($twitter->find('a')) > 0){
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
                //twitter timeline
                elseif($data->tag == "figure" && !is_null($data->find('a', 0)) && isset($data->find('a', 0)->attr['class']) && strpos($data->find('a', 0)->attr['class'], "twitter") !== false && strpos($data->find('a', 0)->attr['class'], "timeline") !== false){
                    $twitter_timeline = $data->find('a', 0);
                    $texto .= "<a class='twitter-timeline' href='" . $twitter_timeline->attr['href'] . "'></a><script async src='//platform.twitter.com/widgets.js' charset='utf-8'></script>#";
                }
                //video de youtube
                elseif($data->tag == "figure" && !is_null($data->find('iframe', 0)) && !is_null($data->find('iframe', 0)->attr['src']) && strpos($data->find('iframe', 0)->attr['src'], "youtube") !== false){
                    $video_src = $data->find('iframe', 0);
                    $texto .= "<iframe width='720' height='505' src='" . $video_src->attr['src'] . "' frameborder='0' allowfullscreen style='display: block; margin: 0 auto;'></iframe>#";
                }
                //video de vine
                elseif($data->tag == "figure" && !is_null($data->find('iframe', 0)) && !is_null($data->find('iframe', 0)->attr['src']) && strpos($data->find('iframe', 0)->attr['src'], "vine.co") !== false){
                    $video_src = $data->find('iframe', 0);
                    $texto .= "<iframe width='576' height='576' src='" . $video_src->attr['src'] . "' frameborder='0' style='display: block; margin: 0 auto;' class=' pinged loaded in-view'></iframe>#";
                }
                //storify
                elseif($data->tag == "iframe" && isset($data->attr['src'])){
                    if(strpos($data->attr['src'], "storify") !== false){
                        $texto .= "<iframe width='100%' height='750' src='" . $data->attr['src'] . "' frameborder='no' allowtransparency='true' scrolling='no' style='display: block; border: none; overflow: hidden; width: 810px; max-width: 900px; height: 12256px; min-height: 12256px; background-color: transparent;'></iframe>#";
                    }
                }
                //video de Facebook
                elseif($data->tag == "iframe" && isset($data->attr['src']) && !is_null($data->attr['src']) && strpos($data->attr['src'], "facebook") !== false){
                    $texto .= "<iframe width='720' height='505' src='" . $data->attr['src'] . "' frameborder='0' allowfullscreen style='display: block; margin: 0 auto;'></iframe>#";
                }
                //audio de soundcloud
                elseif($data->tag == "iframe" && isset($data->attr['src']) && !is_null($data->attr['src']) && strpos($data->attr['src'], "soundcloud") !== false){
                    $texto .= "<iframe width='100%' height='166' allowtransparency='true' src='" . $data->attr['src'] . "' frameborder='no' scrolling='no'></iframe>#";
                }
                //foto de Instagram
                elseif($data->tag == "iframe" && isset($data->attr['src']) && !is_null($data->attr['src']) && strpos($data->attr['src'], "instagram") !== false){
                    $texto .= "<iframe height='787' src='" . $data->attr['src'] . "' frameborder='0' scrolling='no' style='border: 0px; margin: 1px auto; max-width: 658px; width: calc(100% - 2px); border-radius: 4px; box-shadow: rgba(0, 0, 0, 0.498039) 0px 0px 1px 0px, rgba(0, 0, 0, 0.14902) 0px 1px 10px 0px; display: block; padding: 0px; background: rgb(255, 255, 255);></iframe><script async defer src=//platform.instagram.com/en_US/embeds.js></script>#";
                }
                //foto de Instagram en blockquote
                elseif($data->tag == "figure" && !is_null($data->find('blockquote', 0)) && strpos($data->find('blockquote', 0)->attr['class'], "instagram") !== false){
                    $instagram_src = $data->find('blockquote', 0)->find('a', 0);
                    $texto .= "<blockquote class='instagram-media' data-instgrm-version='7' style='display: block; margin: 0 auto; background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); max-width: 658px; padding:0; width: 99.375%; width: -webkit-calc(100% - 2px); width: calc(100% - 2px);'><div style='padding:8px;'> <div style=' background:#F8F8F8; line-height:0; margin-top:40px; padding:46.1489088575% 0; text-align:center; width:100%;'> <div style=' background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAsCAMAAAApWqozAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAMUExURczMzPf399fX1+bm5mzY9AMAAADiSURBVDjLvZXbEsMgCES5/P8/t9FuRVCRmU73JWlzosgSIIZURCjo/ad+EQJJB4Hv8BFt+IDpQoCx1wjOSBFhh2XssxEIYn3ulI/6MNReE07UIWJEv8UEOWDS88LY97kqyTliJKKtuYBbruAyVh5wOHiXmpi5we58Ek028czwyuQdLKPG1Bkb4NnM+VeAnfHqn1k4+GPT6uGQcvu2h2OVuIf/gWUFyy8OWEpdyZSa3aVCqpVoVvzZZ2VTnn2wU8qzVjDDetO90GSy9mVLqtgYSy231MxrY6I2gGqjrTY0L8fxCxfCBbhWrsYYAAAAAElFTkSuQmCC); display:block; height:44px; margin:0 auto -44px; position:relative; top:-22px; width:44px;'></div></div><p style=' color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;'><a href='" . $instagram_src->attr['href'] . "' style=' color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none;'</p></div></blockquote><script async defer src='//platform.instagram.com/en_US/embeds.js'></script><br>#";
                }
                else{
                    $texto .= "";
                }
            }
        }
        else{
            throw new Exception("No hay contenido de noticia");
        }

        return $texto;
    }

    public function getImagenes(){
        $imagenes = [];

        $data3 =  $this->html->find('#vplfgp_embeb', 0);
        if(!is_null($data3)){
            $json = $data3->find('script');
            $json = end($json);
            if(!is_null($json)){
                //obtenemos el JSON del tag
                $end = explode("vplfgal", $json->innertext);
                $end = end($end);
                $json_no_inicio = "[" . substr($end, 12, strlen($end));
                $json_no_inicio_ni_fin = explode("false", $json_no_inicio);
                $json_no_inicio_ni_fin = substr(reset($json_no_inicio_ni_fin), 0, -8) . "]}]";
                //convertimos a JSON
                $images_decoded = json_decode($json_no_inicio_ni_fin);
                foreach($images_decoded as $image){
                    $imagenes[] = [
                        'path' => trim("http://www.mdzol.com/" . $image->i),
                        'descripcion' => trim($image->s)
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

        $data4 =  $this->html->find('#section-title', 0);
        if(!is_null($data4)){
            $data41 = $data4->find('a', 0);
            $seccion = $data41->plaintext;
            if(array_key_exists(trim($seccion), $this->secciones_diario)){
                $codigo = $this->secciones_diario[trim($seccion)];
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

        $data4 = $this->html->find('.video', 0);
        if(!is_null($data4)){
            $video_src = $data4->find('iframe', 0);
            if(!is_null($video_src)){
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
    
    public function getLinksPortadaSeccion(){
        $links = [];

        try{
            $portada_items = $this->html->find('.c2p1, .c3p1, .c4p1');
            $seccion = $this->html->find('#section-title', 0)->find('a', 0);
            if(!is_null($portada_items)){                
                foreach($portada_items as $article){ 
                    $data1 = $article->find('.entry-title', 0);
                    $data11 = $article->find('.entry-title', 0)->find('a', 0);
                    if(!is_null($data1) && !is_null($data11)){
                        $links[] = [
                            'titulo' => html_entity_decode($data11->plaintext),
                            'link' => "http://www.mdzol.com" . $data11->href,
                            'seccion' => $seccion->plaintext
                            ];
                    }
                }
            }
        }
        catch (Exception $e) {}

        return $links;
    }
    
    public function getLinksPortadaBusqueda(){
        $links = [];

        try{
            $this->clearNode('script');
            $busqueda_items = $this->html->find('#items', 0);
            if(!is_null($busqueda_items->children)){
                foreach($busqueda_items->children as $article){ 
                    $data1 = $article->find('a', 0);
                    if(!is_null($data1)){
                        $links[] = [
                            'titulo' => html_entity_decode($data1->attr['title']),
                            'link' => "http://www.mdzol.com/" . html_entity_decode($data1->attr['href'])
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

        $rss = TableRegistry::get('Rsses')->find()
                ->where(['habilitado', 'Portales.codigo' => $this->codigo ])
                ->contain(['Portales'])
                ->first();

        $state = $this->getStateHeaderXml($rss->url); 
        if($state['ok']){
            $urls = \Cake\Core\Configure::read('urls');
            foreach($urls as $url){
                @$this->setHtmlDomFromString($url, $this->getStreamContext());
                $articulo = TableRegistry::get('Articulos')->newEntity();
                $articulo->texto = $this->getContenido();
                $articulo->titulo = "TITULO";
                $articulo->descripcion = "DESCRIPCION";
                $articulo->creado = date("Y-m-d H:i:s");
                $articulo->url_video = $this->getVideo();
                $articulo->categoria_id = 14;
                $articulo->publicado = date("Y-m-d H:i:s");
                $articulo->portal_id = 4;
                $articulo->habilitado = true;
                $articulo->url_rss = html_entity_decode(trim($url));
                $articulo->visitas = 0;
                TableRegistry::get('Articulos')->save($articulo);
            }
        }
    }
}