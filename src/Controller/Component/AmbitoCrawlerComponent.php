<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use simple_html_dom;

/**
 * Description of AmbitoCrawlerComponent
 *
 * @author Jesús Serna
 */
class AmbitoCrawlerComponent extends BaseCrawlerComponent{

    private $secciones_diario = [
        'información general' => 'NACIONALES',
        'tecnología' => 'TECNOLOGIA',
        'política' => 'POLITICA',
        'espectáculos' => 'ESPECTACULO',
        'campo' => 'ECONOMIA',
        'agro' => 'ECONOMIA',
        'ambito biz' => 'NACIONALES',
        'internacionales' => 'INTERNACIONALES',
        'economía' => 'ECONOMIA'
        ];

    private $excluye_seccion = ['editorial'];

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
            $state = $this->getStateHeaderXml($rss->url);
            if($state['ok'])
            {
                $noticias = @simplexml_load_file($rss->url);
                if(!$noticias)return $guardados[$this->codigo][]= "No se pudo acceder al rss";
                if (isset($noticias)) { 
                    $i = 0;
                    foreach ($noticias->channel->item as $noticia) { 
                        $i++;
                        if($this->existTitle((string)$noticia->title) && !in_array(trim($noticia->title),$guardados[$this->codigo]))                        
                        {
                            try{
                                @$this->setHtmlDomFromUrl((string)$noticia->link);  
                                if($this->html){
                                    $this->clearNode('script');
                                    $articulo = $this->articulosTable->newEntity(['associated' => ['PalabrasClaves']]);
                                    $articulo->publicado = $this->verificarIntegridadFechaNoticia($this->getFechaPublicadoRss((string)$noticia->pubDate));
                                    $articulo->titulo = trim((string)$noticia->title);
                                    $articulo->texto = $this->getContenido();
                                    $articulo->descripcion = (strlen(strip_tags($articulo->texto)) > 170) ? substr(strip_tags($articulo->texto),0,170).'...' : strip_tags($articulo->texto); "";
                                    $articulo->creado = date("Y-m-d H:i:s");
                                    $articulo->categoria_id = $this->getCategorias(trim((string)$noticia->category));
                                    $articulo->portal_id = $rss->portal_id;
                                    $articulo->habilitado = true;
                                    $articulo->url_rss = trim((string)$noticia->link);                            
                                    $articulo->visitas = 0;

                                    if ($this->articulosTable->save($articulo)) {
                                        if(count($palabras_claves = $this->getPalabrasClave())> 0 ){
                                            $articulo = $this->articulosTable->get($articulo->id);
                                            $articulo->palabras_claves = $palabras_claves;
                                            $this->articulosTable->save($articulo);
                                        }
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
                        }
                    }                
                }
            }
            else{
                $guardados[$this->codigo][]=$state['state'];
            } 
        }
        return $guardados;
    }    
        
    
    public function getContenido(){
        $pattern = "/<p[^>]*><\\/p[^>]*>/"; 
        $texto = "";
        $data2 = $this->html->getElementById('textoDespliegue');

        if($data2 != null && trim(strip_tags($data2)) != ""){
            $texto_p = "<span>".html_entity_decode(trim(strip_tags($data2,'<br><strong><em><b>')))."</span>";
            return $texto_p;
        }
        else{
            throw new Exception("No hay contenido de noticia");
        }
    }
    
    public function getImagenes(){
        $imagenes = [];
        $data3 =  $this->html->getElementById('imgDesp');
        if($data3 != null && isset($data3->attr['src'])){
            $imagenes[] = ["titulo" => null, "path" => $data3->attr['src']];
        }    
        return $imagenes;        
    }  
    
    public function getPalabrasClave(){ 
        $array_palabras_claves = [];
        $palabras_claves = BaseCrawlerComponent::getPalabrasClaves();
        if(!is_null($palabras_claves) && isset($palabras_claves->attr['content'])){
            $lista_palabras = explode(',', $palabras_claves->attr['content']);
            
            //Se quita las repeticiones no case-sensitive
            $lista_palabras = array_intersect_key(
                    $lista_palabras,
                    array_unique(array_map("strtolower",$lista_palabras))
                    );
            
            foreach($lista_palabras as $palabra){
                if(($palabra = trim($palabra)) == ""){
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
                
                array_push($array_palabras_claves,$palabra_clave);
            }
        }
        return $array_palabras_claves;
    }   
    
    public function getFechaPublicadoRss($date_string){
        $date_pub = strtotime($date_string);
        return date("Y-m-d H:i:s", $date_pub);
    }
    
    public function getCategorias($categoria){ 
        $codigo = 'EXTRA';
        $categoria = strtolower($categoria);
        if(!empty($categoria)){            
            if(array_key_exists($categoria, $this->secciones_diario)){
                $codigo = $this->secciones_diario[$categoria];
            }
        }
        
        $categoria_id = TableRegistry::get('Categorias')
                        ->find()
                        ->where(['Categorias.codigo' => $codigo])
                        ->first()
                        ->id; 
        
        return $categoria_id;
    }
}
