<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Cache\Cache;
use Cake\I18n\Date;

/**
 * TemasDiaPalabraClave Controller
 *
 * @property \App\Model\Table\TemasDiaPalabraClaveTable $TemasDiaPalabraClave
 */
class TemasDiaPalabraClaveController extends AppController
{

    public function initialize() {
        parent::initialize();
        $this->loadModel('Articulos');
        $this->loadComponent('TemasDestacadosCrawler');
    }

    public function beforeFilter(\Cake\Event\Event $event){
        parent::beforeFilter($event);
        $this->Auth->allow(['obtenerTemasDestacados']);
        $this->viewBuilder()->layout('cms');
    }

    /**
     * obtenerTemasDestacados method
     *
     * @return array of noticias and keywords
     */
     public function obtenerTemasDestacados(){
        $palabras_clave = [];
        //obtenemos todos los títulos
        $titulos = $this->TemasDestacadosCrawler->baseCrawler();
        //armamos un arreglo con todas las palabras de los títulos
        foreach($titulos as $key => $titulo){
            if(strpos($titulo, "Error") !== false){
                unset($titulos[$key]);
            }
            $titulo_sin_signos = preg_replace('/¡!¿?:,"#$“”/', '', $titulo);
            $titulo_sin_signos_con_permitidas = preg_replace('/[^A-Za-záéíóúÑñ\-]/', ' ', $titulo_sin_signos);
            $palabras_clave = array_merge($palabras_clave, explode(' ', $titulo_sin_signos_con_permitidas));
        }
        //sacamos las palabras irrelevantes
        $claves = $this->verificarExclusion($palabras_clave);
        $claves = $this->verificarAmbiguedad($claves);
        //guardamos las claves nuevas
        if(count($claves) == 5){
            $this->guardarClaves($claves);
        }
        //retornamos el resultado a la vista
        $this->set('titulos', $titulos);
        $this->set(compact('claves'));
        $this->render('ejecutar_temas_destacados');
    }

    /**
     * verificarExclusion method
     *
     * @return array of keywords without excluded words
     */
    public function verificarExclusion($palabras_clave){
        //obtenemos la lista de palabras excluídas y ambiguas
        $exclusion = Configure::read('exclusion');

        foreach($palabras_clave as $key => $palabra){
            if(strlen($palabra) < 3 || is_numeric($palabra) == true || in_array($palabra, $exclusion) == true){
                unset($palabras_clave[$key]);
            }
        }

        return $palabras_clave;
    }

    /**
     * verificarAmbiguedad method
     *
     * @return array of keywords without ambiguos words
     */
    public function verificarAmbiguedad($palabras_clave){
        $nombres = Configure::read('nombres');

        foreach($palabras_clave as $key => $palabra){
            if(in_array($palabra, $nombres) === true){
                unset($palabras_clave[$key]);
            }
        }
        //contamos la cantidad de ocurrencias de cada palabra, las ordenamos descendentemente y obtenemos las primeras 5
        $claves = array_count_values($palabras_clave);
        arsort($claves);
        $claves = array_keys($claves);
        $claves = array_slice($claves, 0, 5, true);

        return $claves;
    }

    /**
     * guardarClaves method
     *
     * @return null
     */
    public function guardarClaves($claves){
        //desactivamos las claves anteriores
        $temas_dia_anterior = $this->TemasDiaPalabraClave->find()
                ->where(['actual' == 1])->toArray();
        if(!is_null($temas_dia_anterior)){
            foreach($temas_dia_anterior as $tema){
                $tema->actual = false;
                $this->TemasDiaPalabraClave->save($tema);
            }
        }
        //verificamos si la clave existe, para crearla o actualizarlas
        foreach($claves as $clave){
            $clave_existente = $this->TemasDiaPalabraClave->find()
                ->where(['clave' => $clave])->first();
            if(!is_null($clave_existente)){
                $clave_existente->actual = true;
                $clave_existente->creado = date("Y-m-d H:i:s");
                $this->TemasDiaPalabraClave->save($clave_existente);
            }
            else{
                $clave_diaria = $this->TemasDiaPalabraClave->newEntity();
                $clave_diaria->clave = $clave;
                $clave_diaria->actual = true;
                $clave_diaria->creado = date("Y-m-d H:i:s");
                $this->TemasDiaPalabraClave->save($clave_diaria);
            }
        }
    }

    /**
     * recuperarTemasDestacados method
     *
     * @return array of noticias with keywords
     */
    public function recuperarTemasDestacados(){
        $noticias = [];
        //buscamos las palabras claves obtenidas diariamente
        $claves_dia = $this->recuperarClavesDia();
        //buscamos por cada palabra, la última noticia
        foreach($claves_dia as $clave_dia){
            $noticia = TableRegistry::get('Articulos')->find('all')
                ->select([
                    'Articulos.id',
                    'Articulos.titulo',
                    'Articulos.publicado',
                    'Portales.nombre',
                    'Portales.codigo'
                    ])
                ->where(['Articulos.titulo LIKE' => '%'.$clave_dia->clave.'%'])
                ->contain(['Categorias', 'Portales', 'Imagenes'])
                ->order(['Articulos.publicado' => 'DESC'])->first();
            $noticia['clave'] = $clave_dia->clave;

            if(!is_null($noticia)){
                array_push($noticias, $noticia);
            }
        }

        return $noticias;
    }

    /**
     * recuperarNoticiasDestacadas method
     *
     * @return array of noticias
     */
    public function recuperarNoticiasDestacadas($clave){
        //buscamos las noticias para esa clave
        $fecha = new \DateTime('2014-01-01');
        $query = TableRegistry::get('Articulos')->find('all')
            ->contain(['Categorias', 'Portales', 'Imagenes'])
            ->where(['Articulos.titulo LIKE' => '%'.$clave.'%'])
            ->where(['Articulos.publicado >' => $fecha])
            ->order(['Articulos.publicado' => 'DESC']);

        return $query;
    }

    /**
     * recuperarClavesDia method
     *
     * @return array of keywords
     */
    public function recuperarClavesDia(){
        //buscamos las palabras claves obtenidas diariamente
        if(($claves_dia = Cache::read('claves', 'short-1min')) === false){
            $claves_dia = $this->TemasDiaPalabraClave->find()
                    ->where(['actual'])->toArray();
            Cache::write('claves', $claves_dia, 'short-1min');
        }
        else{
            $claves_dia = Cache::read('claves', 'short-1min');
        }

        return $claves_dia;
    }
}
