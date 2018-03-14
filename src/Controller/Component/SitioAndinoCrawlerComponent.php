<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use simple_html_dom;

/**
 * Description of SitioAndinoCrawlerComponent
 *
 * @author Jesús
 */
class SitioAndinoCrawlerComponent extends BaseCrawlerComponent{   
    
    private $secciones_diario = [
        'politica' => 'POLITICA',
        'sociedad' => 'SOCIEDAD',
        'pais' => 'NACIONALES',
        'mundo' => 'INTERNACIONALES',
        
        'vendimia2016' => 'PROVINCIALES',
        'san-rafel' => 'PROVINCIALES',
        'mendoza' => 'PROVINCIALES',
        'guaymallen' => 'PROVINCIALES',
        'godoy-cruz' => 'PROVINCIALES',
        'las-heras' => 'PROVINCIALES',
        'malargue' => 'PROVINCIALES',
        'general-alvear' => 'PROVINCIALES',
        'tunuyan' => 'PROVINCIALES',
        'san-carlos' => 'PROVINCIALES',
        'tupungato' => 'PROVINCIALES',
        'maipu' => 'PROVINCIALES',
        'lujan' => 'PROVINCIALES',
        'la-valle' => 'PROVINCIALES',
        'san-martin' => 'PROVINCIALES',
        'rivadavia' => 'PROVINCIALES',
        'santa-rosa' => 'PROVINCIALES',
        'junin' => 'PROVINCIALES',
        'la-paz' => 'PROVINCIALES',
        
        'policiales' => 'POLICIALES',
        'judiciales' => 'POLICIALES',
        'andinosports' => 'DEPORTES',
        'cultura' => 'SOCIEDAD',
        'espectaculos' => 'ESPECTACULO',
        
        ];
    private $excluye_seccion = ['humor', 'opinion'];  
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
                @$this->setHtmlDomFromString($rss->url,$this->getStreamContext());
                if(!$this->html)return $guardados[$this->codigo][]= "No se pudo acceder al rss";

                try{
                    if(count($links = $this->getLinksPortada()) > 0){
                        foreach ($links as $link) {
                            if($this->existTitle(trim(strip_tags($link['titulo']))) && !in_array(trim(strip_tags($link['titulo'])),$guardados[$this->codigo]))
                            {
                                try{
                                    //$url = 'http://www.diariouno.com.ar/ovacion/Mascherano-no-quiere-saber-nada-con-River-20151215-0121.html';
                                    @$this->setHtmlDomFromString($rss->url.$link['link'], $this->getStreamContext());  
                                    if($this->html){
                                        $articulo = $this->articulosTable->newEntity();
                                        $articulo->titulo = html_entity_decode(trim(strip_tags($link['titulo'])));
                                        $articulo->descripcion = $this->getDescripcion('');
                                        $articulo->texto = $this->getContenido();
                                        //$articulo->palabras_claves = trim($link['titulo']);
                                        $articulo->creado = date("Y-m-d H:i:s");
                                        $articulo->categoria_id = $this->getCategorias();
                                        $articulo->publicado = $this->verificarIntegridadFechaNoticia($this->getFechaPublicadoHtml());
                                        $articulo->portal_id = $rss->portal_id;
                                        $articulo->habilitado = true;
                                        $articulo->url_rss = $rss->url.$link['link'];                            
                                        $articulo->visitas = 0;
                                        
                                        if ($this->articulosTable->save($articulo)) {
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
                                            return $guardados;
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
    
    public function getDescripcion(){
        $data1 = $this->html->find('div.datetime-description',0);
        if(!is_null($data1) && !is_null($data1->find('span.description',0))){
            return html_entity_decode($data1->find('span.description',0)->plaintext);
        }
        else{
            return '';
        }
    } 
    
    public function getFechaPublicadoHtml(){
        try{
            $data3 = $this->html->find('div.datetime-description', 0);
            
            if(!is_null($data3) && !is_null($data3->find('span.date',0))){
                //Viernes 15 de Enero de 2016
                //Wed, 21 Oct 2015 13:09:20 GMT
                $date_arr = explode(" ",trim($data3->find('span.date',0)->plaintext));
                $date_arr[0] = array_key_exists(strtolower(trim($date_arr[0])),
                        $this->getDaysMap())?$this->getDaysMap()[strtolower(trim($date_arr[0]))]:null;
                $date_arr[3] = array_key_exists(strtolower(trim($date_arr[3])),
                        $this->getMonthsMap())?$this->getMonthsMap()[strtolower(trim(trim($date_arr[3])))]:null;

                $date_str = $date_arr[0].", ".trim($date_arr[1])." ".$date_arr[3]." ".trim($date_arr[5]);

                if($date_pub = strtotime($date_str." ".date("H:i:s"))){
                    return date("Y-m-d H:i:s", strtotime("-50 minutes", $date_pub));
                }
                else{
                    throw new Exception("Error en extraccion de fecha.");
                }
            }
            else{
                throw new Exception("Error en extraccion de fecha.");
            }
            
        }
        catch(Exception $e){
            throw new Exception("Error en fechas");
        }
    }      
    
    public function getContenido(){
        $pattern = "/<p[^>]*><\\/p[^>]*>/"; 
        $texto = "";
        $this->clearNode(".vsmwidget");
        $this->clearNode(".relacionados");
        
        $data2 = $this->html->getElementById('vsmcontent');

        if(!is_null($data2) && !is_null($data2_p = $data2->getElementsByTagName('p'))){
            foreach($data2_p as $data_){
                $texto_p = strip_tags($data_->innertext,'<b><strong>'); 
                $texto_p = preg_replace('/\s\s+|&nbsp;/', '', $texto_p);
                if($texto_p != "" && $texto_p != " "){
                    $texto .= '<p>'.strip_tags(preg_replace('#<iframe id="twitter-widget(.*?)</iframe>#','',$data_->innertext),'<b><strong>').'</p>';
                }
            }
        }
        else{
            throw new Exception("No hay contenido de noticia.");
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
        
    public function getCategorias(){ 
        $codigo = 'EXTRA';
        
        $seccion = $this->html->find('.interior', 0);
        if(!is_null($seccion)){
            $seccion_clases = explode(" ",trim($seccion->class));
            if(count($seccion_clases) > 1){
                if(array_key_exists(strtolower(trim($seccion_clases[1])),$this->secciones_diario)){
                    $codigo = $this->secciones_diario[strtolower(trim($seccion_clases[1]))];
                }
                else if(in_array(strtolower(trim($seccion_clases[1])), $this->excluye_seccion)){
                    throw new Exception("Categoria innecesaria.");
                }
            }
        }
        else{
            throw new Exception("Error en la extraccion de seccion.");
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
            $this->clearNode('.header-wrapper');
            $this->clearNode('.alerta');
            $this->clearNode('.menu-wapper');
            $this->clearNode('.ranking');
            $this->clearNode('.footer-wrapper');
            $this->clearNode('.banner');
            $this->clearNode('.separator');
            $this->clearNode('script');
            
            $portada_items = $this->html->find('.item');
            if(!is_null($portada_items)){                
                foreach($portada_items as $article){
                    if(!is_null($article->find('.title', 0)) && !is_null($article->find('.title', 0)->find('a',0))){
                        $links[] = [
                            'titulo'=>html_entity_decode($article->find('.title', 0)->find('a',0)->plaintext), 
                            'link'=>$article->find('.title', 0)->find('a',0)->href, 
                            //'seccion' => $article->find('.section-link', 0)->plaintext
                            ];
                    }
                }
            }
            
        } 
        catch (Exception $e) {}
        return $links;
    }
}
