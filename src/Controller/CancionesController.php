<?php
namespace App\Controller;

use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;
use Google_Client;
use Google_Service_YouTube;
use DateTime;
require_once(ROOT . DS . 'vendor/getid3/getid3.php');

/**
 * Canciones Controller
 *
 */
class CancionesController extends AppController{

    protected $html = NULL;
    protected $streamContext = NULL;
    protected $url_web = "https://www.youtube.com";
    protected $path = WWW_ROOT . "files/audios/";
    
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
	public function generarListadoCanciones($resultadoDTO){        
        $listado = [];

        try{
            $canciones = glob($this->path . "*.{*}", GLOB_BRACE);
            
            //ver swtich de formatos varios (mp3, mp4, aac, etc)
            
            for($i = 0; $i < count($canciones); $i++){
                $cancion = str_replace(".mp3", "", $canciones[$i]);
                $name = explode('/', $canciones[$i]);
                $name = end($name);
                $name_path = $this->path . DS . $name;
                
                //Lectura de tag ID3
                $id3 = new \getID3();
                $cancion_id3 = $id3->analyze($name_path);
                $data_head = $cancion_id3['tags']['id3v2'];
                $data_info = $cancion_id3['audio'];
                
                $title = (array_key_exists('title', $data_head) != false) ? reset($data_head['title']) : "";
                $artist = (array_key_exists('artist', $data_head) != false) ? reset($data_head['artist']) : "";
                $album = (array_key_exists('album', $data_head) != false) ? reset($data_head['album']) : "";
                $year = (array_key_exists('year', $data_head) != false) ? reset($data_head['year']) : 0;
                $genre = (array_key_exists('genre', $data_head) != false) ? reset($data_head['genre']) : "";
                $filesize = (array_key_exists('filesize', $cancion_id3) != false) ? round(($cancion_id3['filesize'] / 1048576), 2) : 0;
                $sample_rate = (array_key_exists('sample_rate', $data_info) != false) ? $data_info['sample_rate'] : 0;
                $bitrate = (array_key_exists('bitrate', $data_info) != false) ? round(($data_info['bitrate'] / 1000), 2) : 0;
                $dataformat = (array_key_exists('dataformat', $data_info) != false) ? $data_info['dataformat'] : "";

                $cancion_procesada = ['title' => $title, 
                    'artist' => $artist, 
                    'album' => $album, 
                    'year' => $year, 
                    'genre' => $genre,
                    'filesize' => $filesize, 
                    'sample_rate' => $sample_rate, 
                    'bitrate' => $bitrate,
                    '$dataformat' => $dataformat,
                    'resultado' => ''];
                
                array_push($listado, $cancion_procesada);
            }            

            $resultadoDTO = ['error' => false, 'message' => NULL, 'listado' => $listado];
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

                //mejorar la filtracion de los temas ya escaneados agregando un nuevo campo y comprobando por el mismo (agregar sql en repo para tener a mano)
        
		try{			
		    $canciones_descargadas = TableRegistry::get('Canciones')
		    ->find('all')
		    ->where(['Canciones.downloaded' => FALSE, 'Canciones.fecha_scanned' <= new \DateTime])->toArray();
		    
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
		    
		    $resultadoDTO = ['error' => false, 'message' => NULL, 'listado' => $cancion_listado];
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
        $image_path = WWW_ROOT . "files/audios/covers";

        try {            
            $state = $this->getStateHeaderXml($this->url_web);
            
            if($state['ok']){            
                for ($i = 0; $i < count($resultadoDTO['listado']); $i++) {                
                    $cancion = reset($resultadoDTO['listado']);

                    $search = str_replace(" ", "+", $cancion['title']) . "+" . str_replace(" ", "+", $cancion['artist']);               
                    $criteria = [
                        'q' => $search, 
                        'maxResults' => '2'];
                    
                    //Declare all variables and oject that will be used to make all API requests.
                    $dev_key = 'AIzaSyCbLwIWQDllwFAKvnK7_HTfJAwE1fux824';
                    $youtube_client = new Google_Client();
                    $youtube_client->setDeveloperKey($dev_key);
                    $youtube_req = new Google_Service_YouTube($youtube_client);
                    $youtube_res = $youtube_req->search->listSearch('id, snippet', $criteria);
                    $video_res = $youtube_res['items'];
                    
                    if(count($video_res) > 0){
                        $video = reset($video_res);

                        //Create the song object with all the data
                        $cancion_to_save = $this->Canciones->newEntity();
                        $cancion_to_save->url = $this->url_web . "/watch?v=" . $video['id']['videoId'];
                        $cancion_to_save->video_id = $video['id']['videoId'];
                        $cancion_to_save->title = $cancion['title'];
                        $cancion_to_save->artist = $cancion['artist'];
                        $cancion_to_save->album = $cancion['album'];
                        $cancion_to_save->duration = "";
                        $cancion_to_save->year = $cancion['year'];                    
                        $cancion_to_save->genre = $cancion['genre'];
                        $cancion_to_save->filesize = $cancion['filesize'];
                        $cancion_to_save->sample_rate = $cancion['sample_rate'];
                        $cancion_to_save->bitrate = $cancion['bitrate'];
                        $cancion_to_save->dataformat = ($cancion['$dataformat']);                    
                        $cancion_to_save->image_path = $video['snippet']['thumbnails']['high']['url'];                        
                        $cancion_to_save->fecha_publish = str_replace(["T", "Z"], " ", $video['snippet']['publishedAt']);
                        $cancion_to_save->creado = date("Y-m-d H:i:s");

                        //ver porque no incrementa el id ni guarda los covers

                        //Save image into the disk
                        if (!is_dir($image_path)) {
                            mkdir($image_path, 0777, true);
                        }
                        
                        $image = file_get_contents($video['snippet']['thumbnails']['high']['url']);
                        file_put_contents($image_path, $image);                        

                        //Save object into the DB
                        if($this->Canciones->save($cancion_to_save)){
                            $resultadoDTO['listado'][$i]['resultado'] = 'La canción se guardó correctamente';
                        }
                        else {
                            $resultadoDTO['listado'][$i]['resultado'] = 'Hubo un error al guardar la canción';
                        }
                    }
                    else {
                        $resultadoDTO['listado'][$i]['resultado'] = 'No hubieron resultados';
                    }
                }
            }
            else {
                $resultadoDTO = ['error' => true, 'message' => "El sitio no está disponible", 'listado' => []];
            }
        }
        catch (Exception $ex) {
            $resultadoDTO = ['error' => true, 'message' => $ex, 'listado' => []];
        }

        return $resultadoDTO;
    }
        
    /**
     * Métodos referidos a chequear disponibilidad de la URL y obtener el DOM en una variable
     *
     * @param string url URL del sitio que se desea chequear.
     * @return array DOM del sitio en una variable.
     */
    public function getStateHeaderXML($url){
        $url_headers = @get_headers($url);
        
        if ($url_headers[0] == 'HTTP/1.1 200 OK' or $url_headers[0] == 'HTTP/1.0 200 OK') {
            //Success
            $response = ['ok' => true, 'state' => $url_headers[0]];
        }
        else {
            //Error
            $response = ['ok' => false, 'state' => $url_headers[0]];
        }

        return $response;
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

    	if($this->path != ""){    		
            $resultadoDTO = $this->generarListadoCanciones($resultadoDTO);

            echo "GENERAR";
            print "<pre>";
            print_r($resultadoDTO);
            print "</pre>";        

            $resultadoDTO = $this->filtrarListadoCanciones($resultadoDTO);

            echo "FILTRAR";
            print "<pre>";
            print_r($resultadoDTO);
            print "</pre>";            
            
            $resultadoDTO = $this->recuperarLinksCanciones($resultadoDTO);

            echo "RECUPERAR";
            print "<pre>";
            print_r($resultadoDTO);
            print "</pre>";
            die;
            
            /*$resultadoDTO = descargarLinksCanciones($resultadoDTO);
            $resultadoDTO = guardarLinksCanciones($resultadoDTO);*/
    	}

    	//seteamos las variables en la vista
        //$this->set(compact('listado_filtrado_guardados'));
    }
}