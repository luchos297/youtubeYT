<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use Exception;
/**
 * Description of WeekendCrawlerComponent
 *
 * @author JGutierrez
 * @date 28/01/2016
 */
class WeekendCrawlerComponent extends BaseCrawlerComponent{

    public function runCrawler(){
        set_time_limit(0);
        $this->rssesTable = TableRegistry::get('Rsses');
        $this->articulosTable = TableRegistry::get('Articulos');

        $rss = $this->rssesTable->find()
            ->where(['habilitado', 'Portales.codigo' => $this->codigo , 'Categorias.codigo' => $this->categoria ])
            ->contain(['Portales','Categorias'])
            ->first();

        $guardados = [$this->codigo =>[]];
        if(!is_null($rss)){
            $context = $this->getStreamContext();
            try
            {
                $content_xml = file_get_contents($rss->url,false,$context);

                if (isset($content_xml)) {
                    $noticias = simplexml_load_string($content_xml);
                    if(!$noticias)return $guardados[$this->codigo][]= "No se pudo acceder al rss";
                    foreach ($noticias->channel->item as $noticia) {
                        if($this->existTitle((string)$noticia->title) && !in_array(trim($noticia->title),$guardados[$this->codigo]))
                        {
                            $this->setHtmlDomFromString($noticia->link,$context);
                            if($this->html){
                                $this->clearNode('script');
                                $articulo = $this->articulosTable->association('Imagenes')->newEntity();
                                $articulo->publicado = $this->verificarIntegridadFechaNoticia($this->getFechaPublicado((string)$noticia->pubDate));
                                $articulo->titulo = trim((string)$noticia->title);
                                $articulo->descripcion = $this->getDescripciones($noticia->description);
                                $articulo->texto = $this->getContenidos($articulo->descripcion);
                                if($articulo->texto!=null) {
                                    $articulo->creado = date("Y-m-d H:i:s");
                                    $articulo->categoria_id = $rss->categoria->id;
                                    $articulo->portal_id = $rss->portal_id;
                                    $articulo->habilitado = true;
                                    $articulo->url_rss = trim($noticia->link);
                                    $articulo->visitas = 0;

                                    if(!is_numeric(strpos($articulo->titulo,'Weekend'))) {
                                        if ($this->articulosTable->save($articulo)) {
                                            $imagenes = $this->getImagenes();
                                            foreach ($imagenes as $imagen) {
                                                $imagen_path = $imagen['path'];
                                                $imagen_name = explode("/", $imagen['path']);
                                                $imagen_name = end($imagen_name);
                                                $imagen_name = explode("?", $imagen_name);
                                                $imagen = $this->saveImagen($imagen_path, '', $imagen_name[0], true);
                                                if ($imagen != null) {
                                                    $this->getConnection()->insert('articulo_imagen', ['articulo_id' => $articulo->id, 'imagen_id' => $imagen->id]);
                                                }
                                            }
                                            $guardados[$this->codigo][] = trim($articulo->titulo);
                                        }
                                    }
                                }
                            }
                        }
                        else{
                            $articulo = $this->articulosTable->findByTitulo($noticia->title)->first();
                            if($articulo->categoria_id != $rss->categoria->id){
                                $articulo->categoria_id = $rss->categoria->id;
                                $this->articulosTable->save($articulo);
                            }

                        }
                    }
                }
            }
            catch (Exception $e){
                $guardados[$this->codigo][]= "No se pudo acceder al rss **";
            }
        }
        return $guardados;
    }

    public function getDescripciones($noticia_description){
        $description = explode('The post',$noticia_description);
        $description = str_replace('Nota con video.', '', $description);
        return $description[0];
    }

    public function getContenidos($description){
        $texto = "";

        $data1 = $this->html->getElementById('articulo');
        if(!empty($data1)){
            $data1 = $data1->getElementsByTagName('p');
        }
        else{
            throw new Exception("Error en parseo");
        }
        $count = count($data1);
        $cnt=0;
        foreach($data1 as $data_){
            if ($cnt == $count - 1 || $cnt == $count - 2 || $cnt == $count - 3) {
                if(is_numeric(strpos($data_,'Fuente'))) {
                    return $texto;
                }
                if(is_numeric(strpos($data_,'Nota'))) {
                    return $texto;
                }
            }
            if(strcmp(trim($data_),trim($description))!=0) {
                $texto_p = strip_tags($data_->innertext, '<b><strong><br>');
                if ($texto_p != "" && $texto_p != " ") {
                    $texto .= '<p>' . strip_tags($data_->innertext, '<b><strong>') . '</p>';
                    $texto = str_replace('[', '', $texto);
                    $texto = str_replace(']', '', $texto);
                    $texto = str_replace('Ver fotogaler&#237;a', '', $texto);
                    $texto = str_replace('Ver&nbsp;fotogaler&#237;a', '', $texto);
                    $texto = str_replace('Ver fotogalería', '', $texto);
                    $texto = str_replace('Ver&nbsp;fotogalería', '', $texto);
                    $texto = str_replace('Nota&nbsp;con&nbsp;video.', '', $texto);
                    $texto = str_replace('Nota con video.', '', $texto);
                }
            }
            $cnt++;
        }
        return $texto;
    }

    public function getImagenes(){
        $imagenes = [];
        try{
            $data2 =  $this->html->getElementById('.foto');
            if(!empty($data2)){
                $imagen = $data2->getElementByTagName('img');
                $imagenes[] = ["path"=>$imagen->attr['src']];
            }
            return $imagenes;
        }
        catch(\Exception $e){
            return $imagenes;
        }
    }

    public function getFechaPublicado($date_string){
        try{
            $date_pub = strtotime($date_string);
            return date("Y-m-d H:i:s", $date_pub);
        }
        catch(\Exception $e){
            return null;
        }
    }
}
