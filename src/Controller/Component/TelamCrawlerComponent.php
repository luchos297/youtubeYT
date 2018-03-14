<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use simple_html_dom;
use \ForceUTF8\Encoding;

/**
 * Description of TelamCrawlerComponent
 *
 * @author Jesús
 */
class TelamCrawlerComponent extends BaseCrawlerComponent{   
    
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
                    if(!$noticias)return $guardados[$this->codigo][]= "No se pudo acceder al rss";
                    foreach ($noticias->channel->item as $noticia) {
                        if($this->existTitle((string)$noticia->title) && !in_array(trim($noticia->title),$guardados[$this->codigo])){
                            try{
                                @$this->setHtmlDomFromUrl($noticia->link); 
                                if($this->html){
                                    $articulo = $this->articulosTable->newEntity();
                                    $articulo->publicado = $this->verificarIntegridadFechaNoticia($this->getFechaPublicadoRss((string)$noticia->pubDate));
                                    $articulo->titulo = trim((string)$noticia->title);
                                    $articulo->descripcion = trim((string)$noticia->description);
                                    $articulo->texto = $this->getContenido();
                                    $articulo->creado = date("Y-m-d H:i:s");
                                    $articulo->categoria_id = $this->getCategorias(null);
                                    $articulo->portal_id = $rss->portal_id;
                                    $articulo->habilitado = true;
                                    $articulo->url_rss = trim((string)$noticia->link);                            
                                    $articulo->visitas = 0;

                                    if ($this->articulosTable->save($articulo)) {
//                                        if(count($palabras_claves = $this->getPalabrasClave())> 0 ){
//                                            $articulo = $this->articulosTable->get($articulo->id);
//                                            $articulo->palabras_claves = $palabras_claves;
//                                            $this->articulosTable->save($articulo);
//                                        }
                                        $imagenes = [];
                                        if(isset($noticia->enclosure) && isset($noticia->enclosure->attributes()->url)){
                                            $imagenes = [["titulo" => null, "path" =>(string)$noticia->enclosure->attributes()->url]];
                                        }   
                                        
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
        $pattern = "/<p[^>]*><\\/p[^>]*>/"; 
        $texto = "";
        $this->clearNode('script');
        $this->clearNode('div.video');
        $this->clearNode('ul.social-fixed-nav');
        $this->clearNode('div.epigrafe');
        $this->clearNode('div.galeria');
        
        $data2 = $this->html->find('.editable-content', 0);

        if(count($data2) > 0){
            $texto_p = "<span>".html_entity_decode(trim(strip_tags($data2,'<br><strong><em><b>')))."</span>";
            return $texto_p;
        }
        else{
            throw new Exception("No hay contenido de noticia");
        }
        
        return $texto;        
    }
    
    public function getImagenes(){
        $imagenes = [];
        
        $data3 =  $this->html->getElementById('vplfgi_embeb');
        if(!is_null($data3)){                
            $imagenes[] = array("titulo" => $data3->attr['alt'], "path"=>$data3->attr['src']);
        }  
        
        return $imagenes;        
    } 
     
    public function getFechaPublicadoRss($date_string){
        $date_pub = strtotime($date_string);
        return date("Y-m-d H:i:s", $date_pub);
    }
    
    public function getCategorias($url){ 
        $codigo = 'EXTRA';
        if(count($this->html->find('.wrapper-ampliado', 0)) > 0 && count($this->html->find('.wrapper-ampliado', 0)->find('.sec',0)) > 0){
            $seccion = $this->html->find('.wrapper-ampliado', 0)->find('.sec',0)->plaintext;
            $seccion = Encoding::fixUTF8($seccion);
            if(array_key_exists(strtolower(trim($seccion)), $this->secciones_diario)){
                $codigo = $this->secciones_diario[strtolower(trim($seccion))];
            }
            else if(in_array(strtolower(trim($seccion)), $this->excluye_seccion)){
                throw new Exception("Categoria innecesaria");
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
        $tags = $this->html->find('.tags-bar', 0);
        try{
            if(!is_null($tags) && !is_null($tags->find('ul',0))){
                foreach($tags->find('ul',0)->find('li') as $li){
                    //$tag = html_entity_decode(strip_tags($li));
                    $tag = mb_convert_encoding(trim($li->find('a',0)->plaintext),'HTML-ENTITIES','utf-8');
                    $tag = utf8_decode(trim($li->find('a',0)->plaintext));
                    if($tag == ""){
                        continue;
                    }

                    $palabra_clave_existente = TableRegistry::get('PalabrasClaves')->findByTexto($tag)->first();
                    if($palabra_clave_existente){
                        $palabra_clave = $palabra_clave_existente;
                    }
                    else{
                        $palabra_clave = TableRegistry::get('PalabrasClaves')->newEntity();
                        $palabra_clave->texto = $tag;
                        $palabra_clave->creado = date("Y-m-d H:i:s");
                    }

                    array_push($array_palabras_claves,$palabra_clave);
                }
            }
        }
        catch(Exception $e){}
        
        return $array_palabras_claves;
    }   
}
