<?php
namespace App\Controller;

use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;
use simple_html_dom;
use Google_Client;
use Google_Service_YouTube;
require_once(ROOT . DS . 'vendor/getid3/getid3.php');

/**
 * Canciones Controller
 *
 */
class CancionesController extends AppController{

    protected $html = NULL;
    protected $streamContext = NULL;
    protected $url_web = "https://www.youtube.com";
    
	public function initialize() {
        parent::initialize();
    }

    public function beforeFilter(\Cake\Event\Event $event){
        $this->Auth->allow(['index']);
    }

	/**
     * Generar la lista de temas en base a un directorio en particular (obteniendo con tag ID3v3 el artista)	
     *
     * @param string Path de la lista de las canciones.
     * @param array DTO con resultado del proceso.
     * @return array mapping Listado de canciones con nombre y artista.
     */
	public function generarListadoCanciones($path, $resultadoDTO){        
        $listado = [];

        try{
            $canciones = glob($path . "*");
            
            for($i = 0; $i < count($canciones); $i++){
                $cancion = str_replace(".mp3", "", $canciones[$i]);
                $name = explode('/', $canciones[$i]);
                $name = end($name);
                $name_path = $path . DS . $name;
                
                //Lectura de tag ID3
                $id3 = new \getID3();
                $cancion_id3 = $id3->analyze($name_path);
                $data_head = $cancion_id3['tags']['id3v1'];
                $data_info = $cancion_id3['audio']['streams'];
                
                $title = (array_key_exists('title', $data_head) != false) ? $data_head['title'] : "";
                $artist = (array_key_exists('artist', $data_head) != false) ? $data_head['artist'] : "";
                $album = (array_key_exists('album', $data_head) != false) ? $data_head['album'] : "";
                $year = (array_key_exists('year', $data_head) != false) ? $data_head['year'] : "";
                $genre = (array_key_exists('genre', $data_head) != false) ? $data_head['genre'] : "";
                $filesize = (array_key_exists('filesize', $cancion_id3) != false) ? $cancion_id3['filesize'] : "";
                $sample_rate = (array_key_exists('sample_rate', $data_info) != false) ? reset($data_info)['sample_rate'] : "";
                $bitrate = (array_key_exists('bitrate', $cancion_id3) != false) ? reset($data_info)['bitrate'] : "";
                
                $cancion_procesada = ['title' => $title, 
                    'artist' => $artist, 
                    'album' => $album, 
                    'year' => $year, 
                    'genre' => $genre,
                    'filesize' => $filesize, 
                    'sample_rate' => $sample_rate, 
                    'bitrate' => $bitrate];
                
                array_push($listado, $cancion_procesada);
            }            

            $resultadoDTO = ['error' => false, 'message' => "", 'listado' => $listado];
        }
        catch (Exception $ex) {
            $resultadoDTO = ['error' => true, 'message' => $ex, 'listado' => []];
        }

        return $resultadoDTO;
    }

	/**
     * Revisar que temas ya fueron buscados y solo dejar los nuevos
     *
     * @param array DTO con resultado del proceso, incluyendo un listado (array mapping) de canciones con nombre y artista.
     * @return array mapping Listado de canciones con nombre y artista filtrados.
     */
    public function filtrarListadoCanciones($resultadoDTO){       
        $listado_filtrado = [];
        $cancion_listado = $resultadoDTO['listado'];

		try{			
		    $canciones_descargadas = TableRegistry::get('Canciones')
		    ->find('all')
		    ->where(['Canciones.downloaded'=> FALSE, 'Canciones.fecha_scanned' <= new \DateTime])->toArray();
		    
		    foreach($cancion_listado as $cancion_a_procesar){
		        $i = 0;
		        foreach($canciones_descargadas as $cancion_a_comparar){
		            if(strtolower(reset($cancion_a_procesar['title'])) === strtolower(($cancion_a_comparar->title)) &&
		                strtolower(reset($cancion_a_procesar['album'])) === strtolower(($cancion_a_comparar->album)) &&
		                strtolower(reset($cancion_a_procesar['artist'])) === strtolower(($cancion_a_comparar->artist))){
		                    unset($cancion_listado[$i]);
		            }
		        }
		        $i++;
		    }
		    
		    $resultadoDTO = ['error' => false, 'message' => "", 'listado' => $cancion_listado];
		}
		catch (Exception $ex) {
		    $resultadoDTO = ['error' => true, 'message' => $ex, 'listado' => []];
		}

        return $resultadoDTO;
    }

     /**
     * Buscar para cada tema el enlace con ID con el siguiente patron: https://www.youtube.com/results?search_query=+criterio reemplazando los espacios por +
     *
     * @param array DTO con resultado del proceso, incluyendo un listado (array mapping) de canciones con nombre y artista filtrados.
     * @return array mapping Listado de canciones con nombre, artista y urls.
     */
    public function recuperarLinksCanciones($resultadoDTO){  
        $listado = [];
        
        try {
	    foreach ($resultadoDTO['listado'] as $cancion) {                
                $search = str_replace(" ", "+", reset($cancion['title'])) . "+" . str_replace(" ", "+", reset($cancion['artist']));               
                $criteria = [
                    'q' => $search, 
                    'maxResults' => '5'];
        
                //Declare all variables and oject that will be used to make all API requests.
                $dev_key = 'AIzaSyCbLwIWQDllwFAKvnK7_HTfJAwE1fux824';
                $youtube_client = new Google_Client();
                $youtube_client->setDeveloperKey($dev_key);
                $youtube_req = new Google_Service_YouTube($youtube_client);
                $youtube_res = $youtube_req->search->listSearch('id,snippet', $criteria);

                print "<pre>";
                print_r($youtube_res);
                print "</pre>";
                die;


                /*
                $cancion = $this->cancionesTable->newEntity();
                $cancion->url = ;
                $cancion->video_id = ;
                $cancion->name = ;
                $cancion->artist = ;
                $cancion->album = ;
                $cancion->duration = ;
                $cancion->image_path = ;
                $cancion->downloaded = true;                
                $cancion->fecha_publish = 
                $cancion->creado = date("Y-m-d H:i:s");*/

                if($this->cancionesTable->save($cancion)){
                    $cancion = ['resultado' => 'La canción se guardó correctamente'];
                }
                else {
                    $cancion = ['resultado' => 'Hubo un error al guardar la canción'];
                }
                
                array_push($cancion, $listado);
      	    }

      	    $resultadoDTO = ['error' => false, 'message' => "", 'listado' => $listado];            
        }
        catch (Exception $ex) {
            $resultadoDTO = ['error' => true, 'message' => $ex, 'listado' => []];
        }

        return $resultadoDTO;
    }
    
    /**
     * Obtiene los links de cada tema que haya en la lista de busqueda
     *
     * @param array Lista de temas con nombre y artista.
     * @return array mapping Listado de canciones con nombre, artista, url y bandera de descarga.
     */    
    public function getLinksTemas(){
        $tema_links = [];

        try{
            $this->clearNode('.header-wrapper');
            $this->clearNode('.menu-wapper');
            $this->clearNode('.footer-wrapper');
            $this->clearNode('.banner');
            $this->clearNode('.separator');
            $this->clearNode('script');

            $links_cancion = $this->html->find('#contents');

            var_dump($links_cancion);
            die;

            if(!is_null($portada_items)){
                foreach($portada_items as $article){
                    if(!is_null($article->find('.title', 0)) && !is_null($article->find('.title', 0)->find('a', 0))){
                        $links[] = [
                           'titulo' => html_entity_decode($article->find('.title', 0)->find('a', 0)->plaintext),
                           'link' => $article->find('.title', 0)->find('a', 0)->href,
                           'seccion' => $article->find('.section', 0)->plaintext
                        ];
                    }
                }
            }
        }
        catch (Exception $e) {}
    
        return $tema_links;
    }
    
    /**
     * Métodos referidos a chequear disponibilidad de la URL y obtener el DOM en una variable
     *
     * @param string url URL del sitio que se desea chequear.
     * @return array DOM del sitio en una variable.
     */
    public function getStateHeaderXML($url){
        $url_headers = @get_headers($url);
        
        if($url_headers[0] == 'HTTP/1.1 200 OK' or $url_headers[0] == 'HTTP/1.0 200 OK') {
                $response = ['ok' => true, 'state' => $url_headers[0]];
            }
            else {
                // Error
                $response = ['ok' => false, 'state' => $url_headers[0]];
            }
            
            return $response;
    }
    
    public function setHtmlDomFromString($url, $context){
        $this->html = new simple_html_dom();
        $this->html->load(@file_get_contents($url, false, $context), true);
    }
    
    public function getStreamContext(){
        return $this->streamContext;
    }

    /**
     * Descargar los videos en un path y marcar los que hayan sido descargados
     *
     * @param array DTO con resultado del proceso, incluyendo un listado (array mapping) de canciones con nombre, artista y urls.
     * @return array mapping Listado de canciones con nombre, artista, url y bandera de descarga.
     */
    public function descargarLinksCanciones($resultadoDTO){
    	$listado_urls = [];

    	try{

        
    	    $resultadoDTO = ['error' => false, 'message' => "", 'listado' => $cancion_listado];
    	}
    	catch (Exception $ex) {
    	    $resultadoDTO = ['error' => true, 'message' => $ex, 'listado' => []];
    	}

        return $resultadoDTO;
    }
    
    public function clearNode($selector){
        
        foreach ($this->html->find($selector) as $node) {
            $node->outertext = '';
        }
        
        $this->html->load($this->html->save());
    }

    /**
     * Guardar los links en la BD que fueron descargados (con exito o no con su respectiva bandera)
	 * 
     * @colums: id, url, video_id, duration, artist, album, year, fecha_scanned, fecha_publish, image_path, downloaded
     * @param array DTO con resultado del proceso, incluyendo un listado (array mapping) de canciones con nombre, artista, urls y bandera de descarga.
     * @return array mapping Listado de canciones guardados.
     */
    public function guardarLinksCanciones($resultadoDTO){
    	$listado_urls = [];

    	try{
    	    
    	    
        
    	    $resultadoDTO = ['error' => false, 'message' => "", 'listado' => $cancion_listado];
    	}
    	catch (Exception $ex) {
    	    $resultadoDTO = ['error' => true, 'message' => $ex, 'listado' => []];
    	}

        return $resultadoDTO;
    }

    public function index(){
    	$resultadoDTO = ["", "", []];
    	$listado = [];
    	$listado_filtrado_guardados = [];
    	$path = WWW_ROOT . "files/audios/";

    	if($path != ""){    		
    		$resultadoDTO = $this->generarListadoCanciones($path, $resultadoDTO);    		
    		$resultadoDTO = $this->filtrarListadoCanciones($resultadoDTO);    		
    		$resultadoDTO = $this->recuperarLinksCanciones($resultadoDTO);		
			
			/*$resultadoDTO = descargarLinksCanciones($resultadoDTO);
			$resultadoDTO = guardarLinksCanciones($resultadoDTO);*/
    	}

    	//seteamos las variables en la vista
        //$this->set(compact('listado_filtrado_guardados'));
    }
}