<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Canciones Controller
 *
 */
class CancionesController extends AppController{

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

        $canciones = scandir($path);

        
		print_r($canciones);

		die("JAJA");


        /*try{
        	$resultadoDTO = [false, "", $listado];
        }
		catch (Exception $ex) {
			$resultadoDTO = [true, $ex, []];
		}*/

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
		
		try{			

        
			$resultadoDTO = [false, "", $listado_filtrado];
        }
		catch (Exception $ex) {
			$resultadoDTO = [true, $ex, []];
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
    	$listado_links = [];

    	try{

        
			$resultadoDTO = [false, "", $listado_links];
        }
		catch (Exception $ex) {
			$resultadoDTO = [true, $ex, []];
		}

        return $resultadoDTO;
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

        
			$resultadoDTO = [false, "", $listado_urls];
        }
		catch (Exception $ex) {
			$resultadoDTO = [true, $ex, []];
		}

        return $resultadoDTO;
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

        
			$resultadoDTO = [false, "", $listado_urls];
        }
		catch (Exception $ex) {
			$resultadoDTO = [true, $ex, []];
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
    		/*$resultadoDTO = filtrarListadoCanciones($resultadoDTO);    		
    		$resultadoDTO = recuperarLinksCanciones($resultadoDTO);    		
			$resultadoDTO = descargarLinksCanciones($resultadoDTO);
			$resultadoDTO = guardarLinksCanciones($resultadoDTO);*/
    	}

    	//seteamos las variables en la vista
        //$this->set(compact('listado_filtrado_guardados'));
    }
}