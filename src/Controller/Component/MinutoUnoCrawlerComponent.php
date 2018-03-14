<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use simple_html_dom;

/**
 * Description of MinutoUnoCrawlerComponent
 *
 * @author Jesús
 */
class MinutoUnoCrawlerComponent extends BaseCrawlerComponent{   
    
    private $secciones_diario = [
        'tecno' => 'TECNOLOGIA',
        'política' => 'POLITICA',
        'mundo' => 'INTERNACIONALES',
        'deportes' => 'DEPORTES',
        'sociedad' => 'SOCIEDAD',
        'entretenimientos' => 'SOCIEDAD',
        'economía' => 'ECONOMIA',
        ];
    private $excluye_seccion = ['humor', 'opinión'];  
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
        
        $guardados = [$this->codigo =>[]];
        if(!is_null($rss)){
            $state = $this->getStateHeaderXml($rss->url); 
            if($state['ok'])
            {  
                $intentos = 15;
                while($intentos > 0){
                    if(!($noticias = @simplexml_load_file((string)$rss->url))){
                        sleep(1);
                        $intentos--;
                    }
                    else{
                        break;
                    }
                }
                
                if(!$noticias)return $guardados[$this->codigo] = "No se pudo acceder al rss. Intentos de acceso agotados.";
                if (isset($noticias)) {
                    foreach ($noticias->channel->item as $noticia) {
                        if($this->existTitle((string)$noticia->title) && !in_array(trim($noticia->title),$guardados[$this->codigo])){
                            try{                                
                                $intentos = 5;
                                while($intentos > 0){
                                    
                                    if(!($dom = file_get_contents(trim((string)$noticia->link), false, $this->getDeflateContext()))){
                                        sleep(1);
                                        $intentos--;
                                    }
                                    else{
                                        break;
                                    }
                                }
                                
                                $this->setHtmlDomFromContent($dom);
                                
                                if($this->html){
                                    $this->clearNode('script');
                                    $articulo = $this->articulosTable->newEntity();
                                    $articulo->publicado = $this->verificarIntegridadFechaNoticia($this->getFechaPublicadoRss((string)$noticia->pubDate));
                                    $articulo->titulo = trim((string)$noticia->title);
                                    $articulo->descripcion = trim(strip_tags((string)$noticia->description));
                                    $articulo->texto = $this->getContenido();
                                    $articulo->creado = date("Y-m-d H:i:s");
                                    if(count($this->secciones_colores) == 0){
                                       $this->getCategoriasColores(); 
                                    }
                                    $articulo->categoria_id = $this->getCategorias();
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
                $guardados[$this->codigo][]= "No se pudo acceder al rss.";
            }
        }
        return $guardados;
    }    
        
    public function getContenido(){ 
        $texto = "";
        $this->clearNode('.noteRelated-i');
        $this->clearNode('.embed_cont');
        
        $data2 = $this->html->find('.article-content', 0);
        
        if(count($data2) > 0 ){
            $texto = "<span>".html_entity_decode(trim(strip_tags($data2->innertext,'<br><strong><em><b><i><p>')))."</span>";

            return $texto;
        }
        else{
            throw new Exception("No hay contenido de noticia");
        }
        
        return $texto;        
    }
    
    public function getImagenes(){
        $imagenes = [];
        
        $data3 =  $this->html->find('meta[property="og:image"]', 0);
        if(!is_null($data3) && isset($data3->attr['content'])){   
            $imagenes[] = array("titulo" => "", "path"=>$data3->attr['content']);
        }  
        
        return $imagenes;        
    }    
     
    public function getFechaPublicadoRss($date_string){
        $date_pub = strtotime($date_string);
        return date("Y-m-d H:i:s", $date_pub);
    }
    
    public function getCategoriasColores(){
        try{
            $menu_secciones = $this->html->find('div.navbar-sections', 0);
            if(count($menu_secciones) > 0 && count($menu_secciones->find('.li')) > 0){
                foreach($menu_secciones->find('.li') as $li){
                    if(isset(explode(" ", trim($li->class))[1]) && count($li->find('a',0)) > 0){
                        $this->secciones_colores[explode(" ", trim($li->class))[1]] =  strtolower($li->find('a',0)->plaintext);
                    }
                }
            }
            if(count($this->secciones_colores) < 2){
                throw new Exception("Error en extraccion de colores en categorias.");
            }
        }
        catch (Exception $e){
            throw new Exception("Error en extraccion de colores en categorias.");
        }
    }
    
    public function getCategorias(){ 
        $codigo = 'EXTRA';
        
        $seccion = $this->html->find('.article-detail-heading', 0);
        if(count($seccion) > 0 && count($seccion->find('.row', 0)->find('.tag',0)) > 0){
            $seccion_clases = $seccion->find('.row', 0)->find('.tag',0)->class;
            if(strlen($seccion_clases) > 0){
                $seccion_clases_lista = explode(" ", trim($seccion_clases));
                if(array_key_exists(trim(end($seccion_clases_lista)),$this->secciones_colores)){
                    $codigo = $this->secciones_diario[$this->secciones_colores[end($seccion_clases_lista)]];
                }
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
}
