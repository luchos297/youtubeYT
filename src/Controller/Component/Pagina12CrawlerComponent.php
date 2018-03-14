<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use simple_html_dom;

/**
 * Description of Pagina12CrawlerComponent
 *
 * @author Jesús
 */
class Pagina12CrawlerComponent extends BaseCrawlerComponent{   
    
    private $secciones_diario = [
        'tecnología' => 'TECNOLOGIA',
        'política' => 'POLITICA',
        'mundo' => 'INTERNACIONALES',
        'policiales' => 'POLICIALES',
        'deportes' => 'DEPORTES',
        'sociedad' => 'SOCIEDAD',
        'espectáculos' => 'ESPECTACULO',
        'cultura' => 'SOCIEDAD',
        'economía' => 'ECONOMIA',
        //'turismo' => ''
        ];
    
    private $excluye_seccion = ['humor', 'opinión'];  
        
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
            try
            {
                $content_xml = @file_get_contents($rss->url,false,$context);
                
                if (isset($content_xml)) {               
                    $noticias = simplexml_load_string($content_xml);
                    if(!$noticias)return $guardados[$this->codigo]= "No se pudo acceder al rss.";
                    foreach ($noticias->channel->item as $noticia) {
                        if($this->existTitle((string)$noticia->title) && !in_array(trim($noticia->title),$guardados[$this->codigo])){
                            try{
                                @$this->setHtmlDomFromUrl($noticia->link); 
                                if($this->html){
                                    $articulo = $this->articulosTable->newEntity();
                                    $articulo->publicado = $this->verificarIntegridadFechaNoticia($this->getFechaPublicadoRss((string)$noticia->pubDate));
                                    $articulo->titulo = trim((string)$noticia->title);
                                    $articulo->descripcion = $this->getDescripcion('');
                                    $articulo->texto = $this->getContenido();
                                    $articulo->creado = date("Y-m-d H:i:s");
                                    $articulo->categoria_id = $this->getCategorias(null);
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
                            //break;
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
        
    public function getDescripcion(){
        $data1 = $this->html->find('p.intro',0);
        if(!is_null($data1)){
            return html_entity_decode($data1->plaintext);
        }
        return '';
    }
    
    public function getContenido(){
        $texto = "";
        $this->clearNode('script');
        
        $data2 = $this->html->getElementById('cuerpo');

        if(!is_null($data2) && ($contenido = trim(strip_tags($data2->innertext,'<br><strong><em><b><p>'))) != ""){
            $texto_p = "<span>".html_entity_decode($contenido)."</span>";
            return $texto_p;
        }
        else{
            throw new Exception("No hay contenido de noticia");
        }
        
        return $texto;        
    }
    
    public function getImagenes(){
        $imagenes = [];
        
        $data3 =  $this->html->find('.foto_nota',0);
        if(!is_null($data3)){
            $imagen = $data3->getElementByTagName('img');
            $imagenes[] = array("titulo" => $imagen->title?:null, "path"=>$imagen->attr['src']);
        }  
        return $imagenes;        
    } 
     
    public function getFechaPublicadoRss($date_string){
        $date_pub = strtotime($date_string);
        return date("Y-m-d H:i:s", $date_pub);
    }
    
    public function getCategorias($url){
        $categoria_id = TableRegistry::get('Categorias')
                        ->find()
                        ->where(['Categorias.codigo' => 'NACIONALES'])
                        ->first()
                        ->id; 
        
        return $categoria_id;
    }
}
