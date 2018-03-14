<?php
namespace App\Controller\Component;

use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;

/**
 * Description of MendozaPostCrawlerComponent
 *
 * @author Luciano
 */
class ElCiudadanoCrawlerComponent extends BaseCrawlerComponent{   

    private $excluye_seccion = [];
    private $secciones_colores = [];

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

        $guardados = [$this->codigo =>[]];
        if(!is_null($rss)){
            $state = $this->getStateHeaderXml($url); 
            if($state['ok']){  
                @$this->setHtmlDomFromString($rss->url, $this->getStreamContext());
                if(!$this->html)return $guardados[$this->codigo][]= "No se pudo acceder al rss";

                try{
                    if(count($links = $this->getLinksPortada()) > 0){
                        foreach ($links as $link) {
                            if($this->existTitle(trim(strip_tags($link['titulo']))) && !in_array(trim(strip_tags($link['titulo'])),$guardados[$this->codigo]))
                            {
                                try{
                                    @$this->setHtmlDomFromString($link['link'], $this->getStreamContext());  
                                    if($this->html){
                                        $articulo = $this->articulosTable->newEntity();
                                        $articulo->titulo = html_entity_decode(trim(strip_tags($link['titulo'])));
                                        //$articulo->descripcion = $this->getDescripcion();
                                        $articulo->creado = date("Y-m-d H:i:s");
                                        $articulo->categoria_id = $this->getCategorias();
                                        $articulo->publicado = $this->verificarIntegridadFechaNoticia($this->getFechaPublicadoHtml());
                                        $articulo->texto = $this->getContenido();
                                        $articulo->portal_id = $rss->portal_id;
                                        $articulo->habilitado = true;
                                        $articulo->url_rss = $link['link'];
                                        $articulo->visitas = 0;

                                        if($articulo->texto != ""){
                                            if ($this->articulosTable->save($articulo)) {
                                                if(count($palabras_claves = $this->getPalabrasClave()) > 0 ){
                                                    $articulo = $this->articulosTable->get($articulo->id);
                                                    $articulo->palabras_claves = $palabras_claves;
                                                    $this->articulosTable->save($articulo);
                                                }

                                                $imagenes = $this->getImagenes();
                                                foreach($imagenes as $imagen){
                                                    $imagen_path = $imagen['path'];

                                                    if(preg_match('/.+\.(jpeg|jpg)/', $imagen_path)){
                                                        $imagen_name = ['x.jpg'];
                                                    }
                                                    else if (preg_match('/.+\.(png)/', $imagen_path)){
                                                        $imagen_name = ['x.png'];
                                                    }
                                                    else if (preg_match('/.+\.(gif)/', $imagen_path)){
                                                        $imagen_name = ['x.gif'];
                                                    }  
                                                    else{
                                                        throw new Exception("Formato no soportado");
                                                    }

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

    /*public function getDescripcion(){
        $data1 = $this->html->find('.description-boxed', 0);
        if(!is_null($data1) && !is_null($data1->plaintext)){
            return html_entity_decode($data1->plaintext);
        }
        else{
            return '';
        }
    }*/

    public function getFechaPublicadoHtml(){
        $fecha = '';

        $data3 = $this->html->find('meta[name=shareaholic:article_published_time]', 0);
        if(!is_null($data3) && !is_null($data3->attr['content'])){
            //2016-07-13T09:35:13+00:00
            //2016-07-13 09:35:13
            $fecha = str_replace("T", " ", substr($data3->attr['content'], 0, -6));
        }

        return $fecha;
    }

    public function getContenido(){
        $texto = "";
        $this->clearNode(".shareaholic-canvas");
        $this->clearNode(".fb-comments");
        $this->clearNode('.header-top');
        $this->clearNode('.date');

        $data2 = $this->html->find('.nine', 0)->children;
        $data21 = $data2[0]->children;
        //eliminamos el primer elemento que es la fecha y los dos ultimos que son de FB
        array_slice($data21, 0, count($data21) - 2);

        if(!is_null($data2) && !is_null($data2[0]->children)){
            foreach($data21 as $data_){
                //Si el cuerpo de la noticia es irregular (section dentro del p)
                if($data_->tag == 'section' && !is_null($data_->find('.entry-content', 0))){
                    $texto = '';
                    $data22 = $data_->find('.entry-content', 0);

                    $data221 = $data22->children;
                    array_slice($data221, 0, count($data221) - 2);

                    foreach($data221 as $data__){
                        if(!is_null($data__->find('iframe', 0)) && strpos($data__->find('iframe', 0)->attr['src'], "youtube") !== false){
                            $video_src = $data__->find('iframe', 0);
                            $texto .= "<iframe width='720' height='505' src='" . $video_src->attr['src'] . "' frameborder='0' allowfullscreen style='display: block; margin: 0 auto;'></iframe>#";
                        }
                        elseif(!is_null($data_->find('source', 0)) && strpos($data_->find('source', 0)->attr['src'], "http:") !== false){
                            $video_src = $data_->find('source', 0);
                            $texto .= "<iframe width='425' height='340' src='" . $video_src->attr['src'] . "' frameborder='0' allowfullscreen style='display: block; margin: 0 auto;'></iframe>#";
                        }
                        elseif(!is_null($data__->find('img', 0)) && strpos($data__->find('img', 0)->attr['src'], "http:") !== false){
                            //obtenemos la url de la foto
                            $image_src = $data__->find('img', 0);
                            $texto .= "<img src='" . $image_src->attr['src'] . "' style='display: block; margin: 0 auto;' height='70%' width='70%'>#";
                            //obtenemos el caption de la foto
                            $image_caption = $image_src->attr['alt'];
                            $texto .= '<p align="center">' . '<i>' . $image_caption . '</i>' . '</p>#';
                        }
                        elseif($data__->tag == 'div' && isset($data__->attr['class']) && strpos($data__->attr['class'], "wp-audio-playlist:") !== false && strpos($data__->find('a', 0)->attr['href'], "http:") !== false){
                            $audio_src = $data__->find('a', 0);
                            $texto .= "<center><audio controls><source src='" . $audio_src->attr['href'] . "' type='audio/mp3'></audio></center>&nbsp;#";
                        }
                        elseif(!is_null($data__->find('text', 0)) && !is_null($data__->find('text', 0)->innertext)){
                            $texto_p = strip_tags($data__->innertext, '<b><strong>'); 
                            $texto_p = preg_replace('/\s\s+|&nbsp;/', '', $texto_p);
                            if($texto_p != "" && $texto_p != " " && $texto_p != "Dejá tu opinión" && $texto_p != " comentarios"){
                                $texto .= '<p>' . strip_tags($data__->innertext, '<b><strong>') . '</p>#';
                            }
                        }
                        elseif(!is_null($data__->find('a')) && strpos($data__->find('a', 0)->attr['href'], "t.co") !== false && strpos($data__->find('a', count($data__->find('a')) - 1)->attr['href'], "twitter") !== false){
                            $twitter_src = $data__->find('a');
                            $texto .= "<blockquote align='center' class='twitter-tweet' data-lang='en'><p lang='es' dir='ltr'><a href='" . $twitter_src[0]->attr['href'] . "'></a></p><a href='" . end($twitter_src)->attr['href'] . "'></a></blockquote><script async src='//platform.twitter.com/widgets.js' charset='utf-8'></script>#";
                        }
                        else{
                            $texto .= '';
                        }
                    }
                    //corregimos error de caracter de comillas y otros      
                    $texto_ini_quot = str_replace('&#8220;', '“', $texto);
                    $texto_end_quot = str_replace('&#8221;', '”', $texto_ini_quot);
                    $texto_end_8203 = str_replace('&#8203;', '', $texto_end_quot);

                    return $texto_end_8203;
                }
                else{
                    if(!is_null($data_->find('iframe', 0)) && strpos($data_->find('iframe', 0)->attr['src'], "youtube") !== false){
                        $video_src = $data_->find('iframe', 0);
                        $texto .= "<iframe width='720' height='505' src='" . $video_src->attr['src'] . "' frameborder='0' allowfullscreen style='display: block; margin: 0 auto;'></iframe>#";
                    }
                    elseif(!is_null($data_->find('source', 0)) && strpos($data_->find('source', 0)->attr['src'], "http:") !== false){
                        $video_src = $data_->find('source', 0);
                        $texto .= "<iframe width='425' height='340' src='" . $video_src->attr['src'] . "' frameborder='0' allowfullscreen style='display: block; margin: 0 auto;'></iframe>#";
                    }
                    elseif(!is_null($data_->find('img', 0)) && strpos($data_->find('img', 0)->attr['src'], "http:") !== false){
                        //obtenemos la url de la foto
                        $image_src = $data_->find('img', 0);
                        $texto .= "<img src='" . $image_src->attr['src'] . "' style='display: block; margin: 0 auto;' height='70%' width='70%'>#";
                        //obtenemos el caption de la foto
                        $image_caption = $image_src->attr['alt'];
                        $texto .= '<p align="center">' . '<i>' . $image_caption . '</i>' . '</p>#';
                    }
                    elseif($data_->tag == 'div' && isset($data_->attr['class']) && strpos($data_->attr['class'], "wp-audio-playlist") !== false && strpos($data_->find('a', 0)->attr['href'], "http:") !== false){
                        $audio_src = $data_->find('a', 0);
                        $texto .= "<center><audio controls><source src='" . $audio_src->attr['href'] . "' type='audio/mp3'></audio></center>&nbsp;#";
                    }
                    elseif(!is_null($data_->find('text', 0)) && !is_null($data_->find('text', 0)->innertext)){
                        $texto_p = strip_tags($data_->innertext, '<b><strong>'); 
                        $texto_p = preg_replace('/\s\s+|&nbsp;/', '', $texto_p);
                        if($texto_p != "" && $texto_p != " " && $texto_p != "Dejá tu opinión" && $texto_p != " comentarios"){
                            $texto .= '<p>' . strip_tags($data_->innertext, '<b><strong>') . '</p>#';
                        }
                    }
                    elseif(!is_null($data_->find('a')) && strpos($data_->find('a', 0)->attr['href'], "t.co") !== false && strpos($data_->find('a', count($data_->find('a')) - 1)->attr['href'], "twitter") !== false){
                        $twitter_src = $data_->find('a');
                        $texto .= "<blockquote align='center' class='twitter-tweet' data-lang='en'><p lang='es' dir='ltr'><a href='" . $twitter_src[0]->attr['href'] . "'></a></p><a href='" . end($twitter_src)->attr['href'] . "'></a></blockquote><script async src='//platform.twitter.com/widgets.js' charset='utf-8'></script>#";
                    }
                    else{
                        $texto .= '';
                    }
                }
            }
        }
        else{
            throw new Exception("No hay contenido de la noticia.");
        }
        //corregimos error de caracter de comillas y otros      
        $texto_ini_quot = str_replace('&#8220;', '“', $texto);
        $texto_end_quot = str_replace('&#8221;', '”', $texto_ini_quot);
        $texto_end_8203 = str_replace('&#8203;', '', $texto_end_quot);

        return $texto_end_8203;
    }

    public function getImagenes(){
        $imagenes = [];

        $data4 =  $this->html->find('meta[name="shareaholic:image"]', 0);
        if(!is_null($data4) && isset($data4->attr['content'])){   
            $imagenes[] = array("descripcion" => "", "path" => $data4->attr['content']);
        }

        return $imagenes;
    }

    public function getCategorias(){ 
        $codigo = 'EXTRA';

        $seccion = $this->html->find('.four-no-margin', 0);
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
                case(strpos($seccion->attr['class'], 'futbol') !== false):
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
            $this->clearNode('.tapa-container');
            $this->clearNode('.banner');
            $this->clearNode('.full-image');

            $portada_items = $this->html->find('.wrapper', 0)->getElementsByTagName('h1, h3');

            if(!is_null($portada_items)){
                foreach($portada_items as $item){
                    if(!is_null($item->find('a', 0))){
                        $a = $item->find('a', 0);
                        if(!is_null($a->attr['href']) && !is_null($a->plaintext)){
                            $links[] = [
                                'titulo' => $item->find('a', 0)->plaintext,
                                'link' => $item->find('a', 0)->attr['href']
                                ];
                        }
                    }
                }
            }

        }
        catch (Exception $e) {}

        return $links;
    }

    public function getPalabrasClave(){
        $keys = '';

        $data7 = $this->html->find('meta[name="shareaholic:keywords"]', 0);
        if(!is_null($data7)){
            $keys = explode(",", $data7->attr['content']);
        }

        return $keys;
    }
}
