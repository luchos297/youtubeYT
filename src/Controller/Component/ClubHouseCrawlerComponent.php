<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use Exception;
/**
 * Description of ClubHouseCrawlerComponent
 *
 * @author Jesús Serna
 */
class ClubHouseCrawlerComponent extends BaseCrawlerComponent{
     
    public function runCrawler(){
        set_time_limit(0);
        $this->rssesTable = TableRegistry::get('Rsses');
        $this->articulosTable = TableRegistry::get('Articulos');
        
        $rss = $this->rssesTable->find()
                ->where(['habilitado', 'Portales.codigo' => $this->codigo , 'Categorias.codigo' => $this->categoria ])
                ->contain(['Portales','Categorias'])
                ->first();
        
        
        if(!is_null($rss)){
            $guardados = [$this->categoria =>[]];
            $context = $this->getStreamContext();
            try
            {
                $content_xml = file_get_contents($rss->url,false,$context);
                
                if (isset($content_xml)) {   
                    $noticias = simplexml_load_string($content_xml);
                    if(!$noticias)return $guardados[$this->categoria][]= "No se pudo acceder al rss";
                    foreach ($noticias->channel->item as $noticia) {
                        if($this->existTitle((string)$noticia->title) && !in_array(trim($noticia->title),$guardados[$this->categoria]))
                        {
                            $this->setHtmlDomFromString($noticia->link,$context);
                            if($this->html){
                                $this->clearNode('script');
                                $articulo = $this->articulosTable->association('Imagenes')->newEntity();
                                $articulo->publicado = $this->verificarIntegridadFechaNoticia($this->getFechaPublicadoHtml());
                                $articulo->titulo = trim((string)$noticia->title);
                                $articulo->descripcion = trim((string)$noticia->description);
                                $articulo->texto = $this->getContenido();
                                //$articulo->palabras_claves = trim((string)$noticia->title);
                                $articulo->creado = date("Y-m-d H:i:s");
                                $articulo->categoria_id = $rss->categoria->id;                            
                                $articulo->portal_id = $rss->portal_id;
                                $articulo->habilitado = true;
                                $articulo->url_rss = trim($noticia->link);                            
                                $articulo->visitas = 0;

                                if ($this->articulosTable->save($articulo)) {
                                    $imagenes = $this->getImagenes();
                                    foreach($imagenes as $imagen){
                                        $imagen_path = $rss->portal->url . $imagen['path'];
                                        $imagen_name = explode("/", $imagen['path']);
                                        end($imagen_name);
                                        $imagen = $this->saveImagen($imagen_path, '', current($imagen_name),true);
                                        if($imagen != null){
                                            $this->getConnection()->insert('articulo_imagen', ['articulo_id' => $articulo->id,'imagen_id' => $imagen->id]);
                                        }                               
                                    }
                                    $guardados[$this->categoria][] = trim($articulo->titulo);
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
                $guardados[$this->categoria][]= "No se pudo acceder al rss";
            }            
        }
        return $guardados;
    }  
      
    public function getContenido(){ 
        $texto = "";
        
        $data1 = $this->html->find('.cuerponota')[0]->find('.row-fluid');
        if(isset($data1[2])){
            $data1_p = $data1[2]->find('.span10');
        }  
        else{
            throw new Exception("Error en parseo");
        }
        if(!empty($data1_p)){
            $data1_p = $data1_p[0]->getElementsByTagName('p');
        }
        else{
            throw new Exception("Error en parseo");
        }

        foreach($data1_p as $data_){
            $texto_p = strip_tags($data_->innertext,'<b><strong><br>'); 
            if($texto_p != "" && $texto_p != " "){
                $texto .= '<p>'.strip_tags($data_->innertext,'<b><strong>').'</p>';
            }
        }
        return $texto;
        
    }
    
    public function getImagenes(){
        $imagenes = [];
        try{            
            $data2 =  $this->html->getElementById('carousel');
            if(!empty($data2)){
                $imagen = $data2->find('.active')[0]->getElementByTagName('img');
                $imagenes[] = ["titulo" => $imagen->title?:null, "path"=>$imagen->attr['src']];
            }  
            return $imagenes;    
        }
        catch(\Exception $e){
            return $imagenes;
        }
    }       
    
    public function getFechaPublicadoHtml(){
        try{
            $data3 = $this->html->find('header ul.fecha', 0);
            foreach($data3->find('li') as $li)
            {
                if($li->class == 'notafecha') {
                    //Jueves, 22 de octubre de 2015
                    //Wed, 21 Oct 2015 13:09:20 GMT
                    $date_arr = explode(" ",str_replace(",","",trim($li->plaintext)));
                    $date_arr[0] = array_key_exists(strtolower($date_arr[0]),
                            $this->getDaysMap())?$this->getDaysMap()[strtolower($date_arr[0])]:$date_arr[0];
                    $date_arr[3] = array_key_exists(strtolower($date_arr[3]),
                            $this->getMonthsMap())?$this->getMonthsMap()[strtolower($date_arr[3])]:$date_arr[3];
                    $date_str = $date_arr[0].", ".$date_arr[1]." ".$date_arr[3]." ".$date_arr[5];
                }
                if($li->class == 'edicionimpresa') {
                    $date_pub = strtotime($date_str." 00:00:00 GMT");
                    return date("Y-m-d H:i:s", $date_pub);
                }
                else if($li->class == 'actualizado1'){
                    $time_arr = explode(" ",trim($li->plaintext));
                    $time_str = array_pop($time_arr);

                    $date_pub = strtotime($date_str." ".$time_str.":00 GMT");
                    return date("Y-m-d H:i:s", $date_pub);
                }
            }
        }
        catch(\Exception $e){
            return null;
        }
    }   
    
}
