<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;

class TextosComponent extends Component {


    /**
    * Método que comprueba similitudes entre titulos string
    * @author Jesús Serna
    * @param Array() arrayNotas contiene array bidimensional
    * @return Bool representa la similitud o no de un titulo con una lista de titulos    
    */
    public function compararTitulos($notas, $titulosNotas, $idNotas, $type){
       $this->ids_aceptado = [];
       $this->titulos = $titulosNotas;
       $this->ids_rechazados = [];
       $this->titulosNotas = [];
       $coincidencias = false;
       $similitud = 0;

        try{
            if($type == 'array'){
                foreach ($notas as $nota) {

                    //reviso coincidencia por cada titulo de la lista
                    foreach($this->titulos as $titulo){
                        //$titulo = 'Macri presidente: fotos y todo el glamour en el Colón';
                        //$nota['titulo'] = 'Macri presidente: todas las fotos y el glamour de la gala del Colón';

                        similar_text (strtoupper($titulo) , 
                                strtoupper($nota['titulo']), $similitud);

                        //similitud excede la tolerancia configurada
                        if(ceil($similitud > Configure::read('limite_tolerancia'))){
                            //buffer de ids a noincluir
                            $this->ids_rechazados[] = $nota['id'];
                            $coincidencias = true;
                            break;
                        }
                    }

                    //items aceptados porque no hay coincidencias
                    if(!$coincidencias){
                        $this->titulos[] = $nota['titulo'];
                        $this->ids_aceptado[] = $nota['id'];
                    }
                }
            }
            else if($type == 'entidad'){
                foreach ($notas as $nota) {

                    //reviso coincidencia por cada titulo de la lista
                    foreach($this->titulos as $titulo){
                        similar_text (strtoupper($titulo) ,
                                strtoupper($nota->titulo), $similitud);

                        //similitud excede la tolerancia configurada
                        if(ceil($similitud > Configure::read('limite_tolerancia'))){
                            //buffer de ids a noincluir
                            $this->ids_rechazados[] = $nota->id;
                            $coincidencias = true;
                            break;
                        }
                    }

                    //items aceptados porque no hay coincidencias
                    if(!$coincidencias){
                        $this->titulos[] = $nota->titulo;
                        $this->ids_aceptado[] = $nota->id;
                    }
                }
            }


           /**
            * si no se encontraron similitudes de titulos se procede
            * a actualizar la lista de titulos y id de noticias
            */
            if($coincidencias){
                $this->idNotas = array_merge ($this->ids_rechazados,$idNotas);
                $this->titulosNotas = $titulosNotas;
            }
            else{
                $this->idNotas = array_merge ($this->ids_aceptado,$idNotas);
                $this->titulosNotas = $this->titulos;
            }
            return [
                'coincidencia' => $coincidencias,
                'ids' => $this->idNotas,
                'titulos' => $this->titulosNotas
                    ];
        }
        catch (Exception $e){
            return [
                 'coincidencia' => false,
                 'ids' => [],
                 'titulos' => []
                     ];
        }
    }
}