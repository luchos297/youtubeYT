<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use simple_html_dom;

/**
 * Description of ElSolCrawlerComponent
 *
 * @author Luciano Buttazzoni
 */
class ElSolSocialesCrawlerComponent extends BaseCrawlerComponent{   

    private $secciones_diario = [
        'sociales' => 'SOCIALES'
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
            if($state['ok']){  
                @$this->setHtmlDomFromString($rss->url, $this->getStreamContext());
                if(!$this->html)return $guardados[$this->codigo][]= "No se pudo acceder al rss";

                try{
                    if(count($links = $this->getLinksPortada()) > 0){
                        foreach ($links as $link) {
                            if($this->existTitle(trim(strip_tags($link['titulo']))) && !in_array(trim(strip_tags($link['titulo'])),$guardados[$this->codigo])){
                                try{
                                    @$this->setHtmlDomFromString($link['link'], $this->getStreamContext());  
                                    if($this->html){
                                        $this->clearNode('script');
                                        $articulo = $this->articulosTable->newEntity();
                                        $articulo->publicado = $this->verificarIntegridadFechaNoticia($this->getFechaPublicado());
                                        $articulo->titulo = html_entity_decode(trim(strip_tags($link['titulo'])));
                                        $articulo->descripcion = $this->getDescripcion();
                                        $articulo->texto = $this->getContenido();
                                        $articulo->creado = date("Y-m-d H:i:s");
                                        $articulo->categoria_id = $this->getCategorias();
                                        $articulo->portal_id = $rss->portal_id;
                                        $articulo->habilitado = true;
                                        $articulo->url_rss = $link['link'];
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
                                                $image_caption = $imagen['descripcion'];
                                                $imagen_name = explode("/", $imagen['path']);
                                                end($imagen_name);
                                                $imagen = $this->saveImagen($imagen_path, $image_caption, current($imagen_name), false);                                    
                                                if($imagen != null){
                                                    $this->getConnection()->insert('articulo_imagen', ['articulo_id' => $articulo->id, 'imagen_id' => $imagen->id]);
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
        $descripcion = '';

        $data4 =  $this->html->find('meta[name="description"]', 0);
        if(!is_null($data4) && isset($data4->attr['content'])){   
            $descripcion = $data4->attr['content'];
        }

        return $descripcion;
    }

    public function getContenido(){
        $texto = "";
        $data2 = $this->html->find('.cuerpo', 0);

        if(!is_null($data2) && !is_null($data2->children)){
            foreach($data2->children as $data){
                //imagenes
                if($data->tag == "figure" && !is_null($data->find('img', 0))){
                    $image_src = $data->find('img', 0);
                    $texto .= "<img src='" . "http://gente.elsol.com.ar" . $image_src->attr['src'] . "' style='display: block; margin: 0 auto;' height='70%'' width='70%'>#"; 
                    if(!is_null($data->find('figcaption', 0))){
                        $caption_src = $data->find('figcaption', 0)->find('text', 0);
                        $texto .= '<p align="center">' . '<i>' . $caption_src->plaintext . '</i>' . '</p>#';
                    }
                }
                //videos de youtube en un 'p'
                elseif($data->tag == "p" && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "youtube") !== false){
                    $video_src = $data->find('iframe');
                    foreach($video_src as $video){
                        if(strpos($video->attr['src'], "youtube") !== false){
                            $texto .= "<iframe width='535' height='450' src='" . $video->attr['src'] . "' frameborder='0' allowfullscreen style='display: block; margin: 0 auto;'></iframe>#";
                        }
                    }
                }
                //audios de soundcloud
                elseif($data->tag == "p" && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "soundcloud") !== false){
                    $audio_src = $data->find('iframe', 0);
                    $texto .= "<iframe width='100%' height='166' allowtransparency='true' src='" . $audio_src->attr['src'] . "' frameborder='no' scrolling='no'></iframe>#";
                }
                //slideshare
                elseif($data->tag == "p" && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "slideshare") !== false){
                    $slideshare_src = $data->find('iframe', 0);
                    $texto .= "<iframe allowfullscreen='' frameborder='0' height='714' marginheight='0' marginwidth='0' scrolling='no' src='" . $slideshare_src->attr['src'] . "' style='border:1px solid #CCC; border-width:1px; margin-bottom:5px; max-width: 100%; display: block; margin: 0 auto;' width='668'></iframe>#";
                }
                //videos
                elseif($data->tag == "div" && strpos($data->attr['class'], "video") !== false && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "youtube") !== false){
                    $video_src = $data->find('iframe', 0);
                    $texto .= "<iframe width='535' height='450' src='" . $video_src->attr['src'] . "' frameborder='0' allowfullscreen style='display: block; margin: 0 auto;'></iframe>#";
                }
                //twitter
                elseif($data->tag == "twitterwidget" && !is_null($data->find('a')) && strpos($data->find('a', 0)->attr['href'], "t.co") !== false && strpos($data->find('a', count($data->find('a')) - 1)->attr['href'], "twitter") !== false){
                    $twitter_src = $data->find('a');
                    $texto .= "<blockquote align='center' class='twitter-tweet' data-lang='en'><p lang='es' dir='ltr'><a href='" . $twitter_src[0]->attr['href'] . "'></a></p><a href='" . end($twitter_src)->attr['href'] . "'></a></blockquote><script async src='//platform.twitter.com/widgets.js' charset='utf-8'></script>#";
                }
                //texto
                elseif($data->tag == "p"){
                    $texto_p = strip_tags($data->innertext,'<b><strong>'); 
                    $texto_p = preg_replace('/\s\s+|&nbsp;/', '', $texto_p);
                    if($texto_p != "" && $texto_p != " "){
                        $texto .= '<p>' . strip_tags($data->innertext,'<b><strong>') . '</p>#';
                    }
                }
                else{
                    $texto .= '';
                }
            }
        }
        else{
            throw new Exception("No hay contenido de noticia");
        }

        return $texto;
    }

    public function getImagenes(){
        $imagenes = [];

        $data1 = $this->html->find('.contenedor', 0);
        if(!is_null($data1)){
            foreach($data1->children as $imagen){
                if(!is_null($imagen->find('img', 0)) && isset($imagen->find('img', 0)->attr['data-cfsrc']) && !is_null($imagen->find('figcaption', 0))){
                    $imagenes[] = [
                        'path' => $imagen->find('img', 0)->attr['data-cfsrc'],
                        'descripcion' => $imagen->find('figcaption', 0)->plaintext
                        ];
                }
                elseif(!is_null($imagen->find('img', 0)) && isset($imagen->find('img', 0)->attr['data-cfsrc']) && is_null($imagen->find('figcaption', 0))){
                    $imagenes[] = [
                        'path' => $imagen->find('img', 0)->attr['data-cfsrc'],
                        'descripcion' => ''
                        ];
                }
                elseif(!is_null($imagen->find('img', 0)) && isset($imagen->find('img', 0)->attr['src']) && !is_null($imagen->find('figcaption', 0))){
                    $imagenes[] = [
                        'path' => $imagen->find('img', 0)->attr['src'],
                        'descripcion' => $imagen->find('figcaption', 0)->plaintext
                        ];
                }    
                elseif(!is_null($imagen->find('img', 0)) && isset($imagen->find('img', 0)->attr['src']) && is_null($imagen->find('figcaption', 0))){
                    $imagenes[] = [
                        'path' => $imagen->find('img', 0)->attr['src'],
                        'descripcion' => ''
                        ];
                }
                else{
                    $imagenes[] = [];
                }
            }
        }

        return $imagenes;
    }

    public function getFechaPublicado(){
        $fecha = '';

        $date_pub = $this->html->find('time', 0);
        if(!is_null($date_pub)){
            $fecha = $date_pub->attr['datetime'];
        }

        return $fecha;
    }

    public function getCategorias(){ 
        $codigo = 'EXTRA';

        $data_categoria = $this->html->find('ol.breadcrumb', 0);

        if(!is_null($data_categoria)){
            if(!is_null($data_categoria->find('[itemprop]',0))){
                $categoria = strtolower(trim($data_categoria->find('[itemprop]',0)->plaintext));

                if(array_key_exists($categoria, $this->secciones_diario)){
                    $codigo = $this->secciones_diario[$categoria];
                }
                else if(!is_null($data_categoria->find('[itemprop]',1))){
                    $categoria = strtolower(trim($data_categoria->find('[itemprop]', 1)->plaintext));

                    if(array_key_exists($categoria, $this->secciones_diario)){
                        $codigo = $this->secciones_diario[$categoria];
                    }
                }
            }
        }
        else{
            throw new Exception("No hay breadcrumb.");
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
    
    public function getLinksPortada(){
        $links = [];

        try{

            $destacada = $this->html->find('.notas-destacadas', 0);
            if(!is_null($destacada)){
                $article = $destacada->find('article', 0)->find('h1', 0);
                $links[] = [
                    'titulo' => html_entity_decode($article->find('a', 0)->plaintext),
                    'link' => "http://www.elsol.com.ar" . $article->find('a', 0)->attr['href']
                    ];
            }

            $portada_items = $this->html->find('.notas-listado-v2', 0);
            if(!is_null($portada_items)){                
                foreach($portada_items->children as $article){
                    if(!is_null($article->find('h2', 0)) && !is_null($article->find('h2', 0)->find('a', 0))){
                        $link = $article->find('h2', 0)->find('a', 0);
                        $links[] = [
                            'titulo' => html_entity_decode($link->plaintext),
                            'link' => "http://www.elsol.com.ar" . $link->attr['href']
                            ];
                    }
                }
            }

        }
        catch (Exception $e) {}

        return $links;
    }
}
