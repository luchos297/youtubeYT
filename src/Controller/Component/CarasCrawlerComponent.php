<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use Exception;
/**
 * Description of CarasCrawlerComponent
 *
 * @author JGutierrez
 * @date 27/01/2016
 */
class CarasCrawlerComponent extends BaseCrawlerComponent{

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
                                $articulo->texto = $this->getContenido();
                                $articulo->creado = date("Y-m-d H:i:s");
                                $articulo->categoria_id = $rss->categoria->id;
                                $articulo->portal_id = $rss->portal_id;
                                $articulo->habilitado = true;
                                $articulo->url_rss = trim($noticia->link);
                                $articulo->visitas = 0;

                                if(!is_numeric(strpos($articulo->titulo,'Tapa'))) {
                                    if ($this->articulosTable->save($articulo)) {
                                        if(count($palabras_claves = $this->getPalabrasClave())> 0 ){
                                            $articulo = $this->articulosTable->get($articulo->id);
                                            $articulo->palabras_claves = $palabras_claves;
                                            $this->articulosTable->save($articulo);
                                        }
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
        $description = "";
        $description = explode('The post',$noticia_description);
        return $description[0];
    }

    public function getContenido(){
        $texto = "";

        $data1 = $this->html->getElementById('content');
        if(!empty($data1)){
            $data1 = $data1->getElementsByTagName('p');
        }
        else{
            throw new Exception("Error en parseo");
        }

        foreach($data1 as $data_){
            $texto_p = strip_tags($data_->innertext,'<b><strong><br>');
            if($texto_p != "" && $texto_p != " "){
                $texto .= '<p>'.strip_tags($data_->innertext,'<b><strong><br>').'</p>';
            }
        }
        return $texto;
    }

    public function getImagenes(){
        $imagenes = [];
        try{
            $data2 =  $this->html->getElementById('lafoto');
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
    public function getPalabrasClave(){
        $array_palabras_claves = [];
        $array_palabras = [];
        
        $tags_palabras_claves = BaseCrawlerComponent::getPalabrasClaves(2);
        
        foreach($tags_palabras_claves as $palabras_claves){
            if(isset($palabras_claves->attr['content'])){
                $palabra = trim($palabras_claves->attr['content']);
                
                //evitar repeticiones de tags y vacios
                if(trim($palabra) == "" || in_array($palabra, $array_palabras)){
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
                
                //$array_palabras 
                array_push($array_palabras, $palabra);
                array_push($array_palabras_claves,$palabra_clave);
            }
        }
        
        return $array_palabras_claves;
    }
}
