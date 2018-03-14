<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use Exception;
/**
 * Description of CanchaLlenaCrawlerComponent
 *
 * @author JGutierrez
 * @date 18/01/2015
 */
class CanchaLlenaCrawlerComponent extends BaseCrawlerComponent{

    public function runCrawler(){
        set_time_limit(0);
        $this->rssesTable = TableRegistry::get('Rsses');
        $this->articulosTable = TableRegistry::get('Articulos');

        $rss = $this->rssesTable->find()
            ->where(['habilitado', 'Portales.codigo' => $this->codigo , 'Categorias.codigo' => $this->categoria])
            ->contain(['Portales','Categorias'])
            ->first();

        $guardados = [$this->codigo =>[]];
        if(!is_null($rss)){
            $context = $this->getStreamContext();
            try
            {
                $content_xml = file_get_contents($rss->url);

                if (isset($content_xml)) {
                    $noticias = simplexml_load_string($content_xml);
                    if(!$noticias)return $guardados[$this->codigo][] = "No se pudo acceder al rss";
                    foreach ($noticias->entry as $noticia) {
                        if($this->existTitle((string)$noticia->title) && !in_array(trim($noticia->title),$guardados[$this->codigo]))
                        {
                            try{
                                $this->setHtmlDomFromString((string)$noticia->link['href'], $context);
                                if($this->html){
                                    $this->clearNode('script');
                                    $articulo = $this->articulosTable->association('Imagenes')->newEntity();
                                    $articulo->publicado = $this->verificarIntegridadFechaNoticia($this->getFechaPublicadoRss((string)$noticia->updated));
                                    $articulo->titulo = trim((string)$noticia->title);
                                    $articulo->descripcion = trim((string)$noticia->content->div);
                                    $articulo->texto = $this->getContenido();
                                    //$articulo->palabras_claves = trim((string)$noticia->title);
                                    $articulo->creado = date("Y-m-d H:i:s");
                                    $articulo->categoria_id = $rss->categoria->id;
                                    $articulo->portal_id = $rss->portal_id;
                                    $articulo->habilitado = true;
                                    $articulo->url_rss = trim((string)$noticia->link);
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
        $data1 =  $this->html->getElementById('cuerpo');
        if($data1 != null && $data1->getElementsByTagName('p') != null){
            $data1_p = $data1->getElementsByTagName('p');
        }
        else{
            throw new Exception("Error en parseo");
        }

        foreach($data1_p as $data_){
            $texto_p = strip_tags($data_->innertext,'<b><strong><h2>');
            if($texto_p != "" && $texto_p != " "){
                $texto .= '<p>'.strip_tags($data_->innertext,'<b><strong><h2>').'</p>';
            }
        }
        return $texto;

    }

    public function getImagenes(){
        $imagenes = [];
        $data2 =  $this->html->find('.primer-parrafo',0);
        if($data2 != null && $data2->find('img',0) != null){
            $imagenes[] = array("titulo" => null, "path"=>$data2->find('img',0)->attr['src']);
        }
        else{
            $data2 =  $this->html->find('.encolumnada',0);
            if($data2 != null && $data2->find('img',0) != null){
                if(!empty($data2->find('img',0)->attr['src'])){
                    $imagenes[] = array("titulo" => null, "path"=>substr($data2->find('img',0)->attr['src'], 0, -7)."650.".substr($data2->find('img',0)->attr['src'], -3));
                }
            }
        }
        return $imagenes;
    }

    public function getFechaPublicadoRss($date_string){
        $date_pub = strtotime($date_string);
        return date("Y-m-d H:i:s", $date_pub);
    }
}
