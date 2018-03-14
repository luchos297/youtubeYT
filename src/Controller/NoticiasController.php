<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Cache\Cache;
use Cake\Core\Configure;

/**
 * Noticias Controller
 *
 */
class NoticiasController extends AppController{

    public $articulosTable;

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Textos');
        $this->loadModel('Articulos');
    }

    public function beforeFilter(\Cake\Event\Event $event){
        $this->Auth->allow(['index', 'inicio', 'articulo', 'seccionKeywords', 'secciones', 'portales',
            'revistas', 'radios', 'reproductor', 'television', 'reproductorTv']);
    }

    function index() {
        //$this->redirect('http://www.vistamedios.com/noticias/secciones/PROVINCIALES');
        $this->setAction('inicio');
    }

    function inicio() {

        $this->viewBuilder()->layout('Front/default');

        $articulos = TableRegistry::get('Articulos');
        $parent = TableRegistry::get('Categorias')->findByCodigo('SECCIONES')->first();

        //Temporizador
        /*$time_pre = microtime(true);
        $time_post = microtime(true);
        $exec_time = $time_post - $time_pre;*/

        $titulos = [];
        $ids = [];

        try{
            //Recuperamos la ultima noticia
            $ultimas = [];
            $ultima = $articulos->findUltima();
            if(!is_null($ultima)){
                //comparo los titulos encontrados con el arreglo ya existente
                $comparacion = $this->Textos->compararTitulos($ultima, $titulos, $ids, 'array');
                //valido la coincidencia y actualizo los datos
                if($comparacion['coincidencia']){
                    $ids = array_merge($ids, $comparacion['ids']);
                    $titulos = $comparacion['titulos'];
                }
                else{
                    $titulos = $comparacion['titulos'];
                }
                array_push($ultimas, $ultima);

                $ids = array_merge(array_map(create_function('$o', 'return $o->id;'), $ultimas), $ids);
            }
        }
        catch (Exception $ex) {}

        try{
            //Recuperamos las noticias del carousel
            $carousel_principal = $articulos->findCarousel($ids, $parent->id, 8);
            if(!is_null($carousel_principal)){
                //comparo los titulos encontrados con el arreglo ya existente
                $comparacion = $this->Textos->compararTitulos($carousel_principal, $titulos, $ids, 'array');
                //valido la coincidencia y actualizo los datos
                if($comparacion['coincidencia']){
                    $ids = array_merge($ids, $comparacion['ids']);
                    $titulos = $comparacion['titulos'];
                }
                else{
                    $titulos = $comparacion['titulos'];
                }

                $ids = array_merge(array_map(create_function('$o', 'return $o["id"];'), $carousel_principal), $ids);
            }
        }
        catch (Exception $ex) {}

        try{
            //Recuperamos las noticias de provinciales
            $parte9 = $articulos->findSeccion('PROVINCIALES', 6, $ids);
            if(!is_null($parte9)){
                //comparo los titulos encontrados con el arreglo ya existente
                $comparacion = $this->Textos->compararTitulos($parte9, $titulos, $ids, 'array');
                //valido la coincidencia y actualizo los datos
                if($comparacion['coincidencia']){
                    $ids = array_merge($ids, $comparacion['ids']);
                    $titulos = $comparacion['titulos'];
                }
                else{
                    $titulos = $comparacion['titulos'];
                }

                $ids = array_merge(array_map(create_function('$o', 'return $o["id"];'), $parte9), $ids);
            }
        }
        catch (Exception $ex) {}

        try{
            //Recuperamos las noticias de nacionales
            $parte1 = $articulos->findSeccion('NACIONALES', 5, $ids);
            if(!is_null($parte1)){
                //comparo los titulos encontrados con el arreglo ya existente
                $comparacion = $this->Textos->compararTitulos($parte1, $titulos, $ids, 'array');
                //valido la coincidencia y actualizo los datos
                if($comparacion['coincidencia']){
                    $ids = array_merge($ids, $comparacion['ids']);
                    $titulos = $comparacion['titulos'];
                }
                else{
                    $titulos = $comparacion['titulos'];
                }

                $ids = array_merge(array_map(create_function('$o', 'return $o["id"];'), $parte1), $ids);
            }
        }
        catch (Exception $ex) {}

        /* VER ESTO (este if se saca cuando la cantidad de crawlers de diarios supere los 8)
        if(count($parte1_temp) < 6){
            while(true){
                //recupero
                $parte1_temp2 = $articulos->findDestacadasPorPortal($ids, [$categoria_provincial->id], $parent->id, (6 - count($parte1_temp)));
                //comparo
                $comparacion = $this->Textos->compararTitulos($parte1_temp2, $titulos, $ids, 'array');
                //valido coincidencia y actualizo datos
                if($comparacion['coincidencia']){
                    $ids = array_merge($ids, $comparacion['ids']);
                    $titulos = $comparacion['titulos'];
                }
                else{
                    $titulos = $comparacion['titulos'];
                    break;
                }
            }
        }

        if(strpos(Configure::read('dominio'), "vistamedios") !== false){
            $parte1_temp = array_merge($parte1_temp, $parte1_temp2);
        }*/

        try{
            //Recuperamos las noticias de politica
            $parte10 = $articulos->findSeccion('POLITICA', 4, $ids);
            if(!is_null($parte10)){
                //comparo los titulos encontrados con el arreglo ya existente
                $comparacion = $this->Textos->compararTitulos($parte10, $titulos, $ids, 'array');
                //valido coincidencia y actualizo datos
                if($comparacion['coincidencia']){
                    $ids = array_merge($ids, $comparacion['ids']);
                    $titulos = $comparacion['titulos'];
                }
                else{
                    $titulos = $comparacion['titulos'];
                }

                $ids = array_merge(array_map(create_function('$o', 'return $o["id"];'), $parte10), $ids);
            }
        }
        catch (Exception $ex) {}

        try{
            //Recuperamos las noticias de economia
            $parte11 = $articulos->findSeccion('ECONOMIA', 5, $ids);
            if(!is_null($parte11)){
                //comparo los titulos encontrados con el arreglo ya existente
                $comparacion = $this->Textos->compararTitulos($parte11, $titulos, $ids, 'array');
                //valido coincidencia y actualizo datos
                if($comparacion['coincidencia']){
                    $ids = array_merge($ids, $comparacion['ids']);
                    $titulos = $comparacion['titulos'];
                }
                else{
                    $titulos = $comparacion['titulos'];
                }

                $ids = array_merge(array_map(create_function('$o', 'return $o["id"];'), $parte11), $ids);
            }
        }
        catch (Exception $ex) {}

        try{
            //Recuperamos las noticias de internacionales
            $parte3 = $articulos->findSeccion('INTERNACIONALES', 4, $ids);
            if(!is_null($parte3)){
                //comparo los titulos encontrados con el arreglo ya existente
                $comparacion = $this->Textos->compararTitulos($parte3, $titulos, $ids, 'array');
                //valido coincidencia y actualizo datos
                if($comparacion['coincidencia']){
                    $ids = array_merge($ids, $comparacion['ids']);
                    $titulos = $comparacion['titulos'];
                }
                else{
                    $titulos = $comparacion['titulos'];
                }

                $ids = array_merge(array_map(create_function('$o', 'return $o["id"];'), $parte3), $ids);
            }
        }
        catch (Exception $ex) {}

        try{
            //Recuperamos las noticias de policiales
            $parte8 = $articulos->findSeccion('POLICIALES', 6, $ids);
            if(!is_null($parte8)){
                //comparo los titulos encontrados con el arreglo ya existente
                $comparacion = $this->Textos->compararTitulos($parte8, $titulos, $ids, 'array');
                //valido coincidencia y actualizo datos
                if($comparacion['coincidencia']){
                    $ids = array_merge($ids, $comparacion['ids']);
                    $titulos = $comparacion['titulos'];
                }
                else{
                    $titulos = $comparacion['titulos'];
                }

                $ids = array_merge(array_map(create_function('$o', 'return $o["id"];'), $parte8), $ids);
            }
        }
        catch (Exception $ex) {}

        try{
            //Recuperamos las noticias de deportes
            $parte2 = $articulos->findSeccion('DEPORTES', 4, $ids);
            if(!is_null($parte2)){
                //comparo los titulos encontrados con el arreglo ya existente
                $comparacion = $this->Textos->compararTitulos($parte2, $titulos, $ids, 'array');
                //valido coincidencia y actualizo datos
                if($comparacion['coincidencia']){
                    $ids = array_merge($ids, $comparacion['ids']);
                    $titulos = $comparacion['titulos'];
                }
                else{
                    $titulos = $comparacion['titulos'];
                }

                $ids = array_merge(array_map(create_function('$o', 'return $o["id"];'), $parte2), $ids);
            }
        }
        catch (Exception $ex) {}

        try{
            //Recuperamos las noticias de sociales
            $parte7 = $articulos->findSeccion('SOCIALES', 6, $ids);
            if(!is_null($parte7)){
                //comparo los titulos encontrados con el arreglo ya existente
                $comparacion = $this->Textos->compararTitulos($parte7, $titulos, $ids, 'array');
                //valido coincidencia y actualizo datos
                if($comparacion['coincidencia']){
                    $ids = array_merge($ids, $comparacion['ids']);
                    $titulos = $comparacion['titulos'];
                }
                else{
                    $titulos = $comparacion['titulos'];
                }

                $ids = array_merge(array_map(create_function('$o', 'return $o["id"];'), $parte7), $ids);
            }
        }
        catch (Exception $ex) {}

        try{
            //Recuperamos las noticias de sociedad
            $parte4 = $articulos->findSeccion('SOCIEDAD', 5, $ids);
            if(!is_null($parte4)){
                //comparo los titulos encontrados con el arreglo ya existente
                $comparacion = $this->Textos->compararTitulos($parte4, $titulos, $ids, 'array');
                //valido coincidencia y actualizo datos
                if($comparacion['coincidencia']){
                    $ids = array_merge($ids, $comparacion['ids']);
                    $titulos = $comparacion['titulos'];
                }
                else{
                    $titulos = $comparacion['titulos'];
                }

                $ids = array_merge(array_map(create_function('$o', 'return $o["id"];'), $parte4), $ids);
            }
        }
        catch (Exception $ex) {}

        try{
            //Recuperamos las noticias de espectaculo
            $parte6 = $articulos->findSeccion('ESPECTACULO', 6, $ids);
            if(!is_null($parte6)){
                //comparo los titulos encontrados con el arreglo ya existente
                $comparacion = $this->Textos->compararTitulos($parte6, $titulos, $ids, 'array');
                //valido coincidencia y actualizo datos
                if($comparacion['coincidencia']){
                    $ids = array_merge($ids, $comparacion['ids']);
                    $titulos = $comparacion['titulos'];
                }
                else{
                    $titulos = $comparacion['titulos'];
                }

                $ids = array_merge(array_map(create_function('$o', 'return $o["id"];'), $parte6), $ids);
            }
        }
        catch (Exception $ex) {}

        try{
            //Recuperamos las noticias de tecnologia
            $parte5 = $articulos->findSeccion('TECNOLOGIA', 4, $ids);
            if(!is_null($parte5)){
                //comparo los titulos encontrados con el arreglo ya existente
                $comparacion = $this->Textos->compararTitulos($parte5, $titulos, $ids, 'array');
                //valido coincidencia y actualizo datos
                if($comparacion['coincidencia']){
                    $ids = array_merge($ids, $comparacion['ids']);
                    $titulos = $comparacion['titulos'];
                }
                else{
                    $titulos = $comparacion['titulos'];
                }

                $ids = array_merge(array_map(create_function('$o', 'return $o["id"];'), $parte5), $ids);
            }
        }
        catch (Exception $ex) {}

        /*seccion pequeña de 4 noticias
        $parte9 = $articulos->findSeccion($ids, 'TECNOLOGIA', $parent->id, 15)->toArray();
        $parte9_final = $articulos->filterCantidadMaximaPorPortal($parte9, 4, 2);
        $parte9 = array_reverse($parte9_final);
        $ids = array_merge(array_map(create_function('$o', 'return $o->id;'), $parte9), $ids);

        seccion pequeña de 4 noticias
        /*$parte10 = $articulos->findSeccion($ids, 'SOCIEDAD', $parent->id, 15)->toArray();
        $parte10_final = $articulos->filterCantidadMaximaPorPortal($parte10, 4, 2);
        $parte10 = array_reverse($parte10_final);
        $ids = array_merge(array_map(create_function('$o', 'return $o->id;'), $parte10), $ids);

        seccion pequeña de 4 noticias
        /*$parte11 = $articulos->findSeccion($ids, 'POLICIALES', $parent->id, 15)->toArray();
        $parte11_final = $articulos->filterCantidadMaximaPorPortal($parte11, 4, 2);
        $parte11 = array_reverse($parte11_final);
        $ids = array_merge(array_map(create_function('$o', 'return $o->id;'), $parte11), $ids);*/

        //recuperamos las mas leidas
        $mas_leidos = $this->_masLeidas(5, $parent->id);
        //recuperamos todos los portales
        $portales = $this->portalesActivos();
        //recuperamos el menu
        $menu = $this->_recuperarMenu();
        //recuperamos los portales en el menu
        $portales_menu = $this->portalesEnMenu();
        //recuperamos las publicidades
        $banners_300x250 = $this->_recuperarBanners('HOME', '300x250');
        $banners_728x90 = $this->_recuperarBanners('HOME', '728x90');
        //recuperamos la cartelera de noticias
        $cartelera = $this->cartelera();
        //recuperamos las noticias urgentes
        $especial = $this->especial();
        //recuperamos los temas del dia
        $destacadas = $this->temasDelDia();
        //recuperamos la portada de los diarios
        $portadas = $this->portadaDiarios();

        //seteamos las variables en la vista
        $this->set(compact(
                'ultima', 'carousel_principal', 'parte1', 'parte2', 'parte3', 'parte4', 'parte5', 'parte6', 
                'parte7', 'parte8', 'parte9', 'parte10', 'parte11',
                'mas_leidos', 'portales', 'menu', 'portales_menu', 'banners_300x250', 'banners_728x90',
                'cartelera', 'especial', 'destacadas', 'claves', 'portadas'));
        //$this->set('carousel_principal', json_encode($carousel_principal, JSON_PRETTY_PRINT));
    }

    function articulo($id){
        $this->viewBuilder()->layout('Front/post');

        $titulos = [];
        $ids = [];

        if(!is_null($id)){
            //contador de visitas
            $noticia = TableRegistry::get('Articulos')->get($id, [
                    'contain' => ['Categorias', 'Portales', 'Imagenes']
                ]);
            $noticia->visitas += 1;
            TableRegistry::get('Articulos')->save($noticia);
            //Jueves, 07 de Abril de 2016, 14:43
            $noticia->publicado = $this->getFechaPublicadaTraducida($noticia->publicado->format('D, d \d\e F \d\e Y, H:i'));

            try{
                //recuperamos las ultimas noticias del portal
                $ultimas_por_portal = $this->Articulos->findArticuloPorPortal(array_merge($ids, [$id]), $noticia->portal->id, null, 11);
                if(!is_null($ultimas_por_portal)){
                    //comparo los titulos encontrados con el arreglo ya existente
                    $comparacion = $this->Textos->compararTitulos($ultimas_por_portal, $titulos, $ids, 'entidad');
                    //valido coincidencia y actualizo datos
                    if($comparacion['coincidencia']){
                        $ids = array_merge($ids, $comparacion['ids']);
                        $titulos = array_merge($titulos, $comparacion['titulos']);
                    }
                }
            }
            catch (Exception $ex) {}

            //recuperamos todos los portales
            $portales = $this->portalesActivos();
            //recuperamos el menu
            $menu = $this->_recuperarMenu();
            //recuperamos los portales en el menu
            $portales_menu = $this->portalesEnMenu();
            //recuperamos las publicidades
            $banners_728x90 = $this->_recuperarBanners('HOME', '728x90');

            //seteamos las variables en la vista
            $this->set('_serialize', ['noticia']);
            $this->set(compact('noticia', 'ultimas_por_portal', 'portales', 'menu', 'portales_menu', 'banners_728x90'));
        }
    }

    public function getFechaPublicadaTraducida($fecha){

        //reemplazamos el dia en ingles por español
        if(strpos($fecha, "Mon") !== false){
            $fecha = preg_replace('/Mon/', 'Lunes', $fecha);
        }
        elseif(strpos($fecha, "Tue") !== false){
            $fecha = preg_replace('/Tue/', 'Martes', $fecha);
        }
        elseif(strpos($fecha, "Wed") !== false){
            $fecha = preg_replace('/Wed/', 'Miércoles', $fecha);
        }
        elseif(strpos($fecha, "Thu") !== false){
            $fecha = preg_replace('/Thu/', 'Jueves', $fecha);
        }
        elseif(strpos($fecha, "Fri") !== false){
            $fecha = preg_replace('/Fri/', 'Viernes', $fecha);
        }
        elseif(strpos($fecha, "Sat") !== false){
            $fecha = preg_replace('/Sat/', 'Sábado', $fecha);
        }
        else{
            $fecha = preg_replace('/Sun/', 'Domingo', $fecha);
        }

        //reemplazamos el mes en ingles por español
        if(strpos($fecha, "January") !== false){
            $fecha = preg_replace('/January/', 'Enero', $fecha);
        }
        elseif(strpos($fecha, "February") !== false){
            $fecha = preg_replace('/February/', 'Febrero', $fecha);
        }
        elseif(strpos($fecha, "March") !== false){
            $fecha = preg_replace('/March/', 'Marzo', $fecha);
        }
        elseif(strpos($fecha, "April") !== false){
            $fecha = preg_replace('/April/', 'Abril', $fecha);
        }
        elseif(strpos($fecha, "May") !== false){
            $fecha = preg_replace('/May/', 'Mayo', $fecha);
        }
        elseif(strpos($fecha, "June") !== false){
            $fecha = preg_replace('/June/', 'Junio', $fecha);
        }
        elseif(strpos($fecha, "July") !== false){
            $fecha = preg_replace('/July/', 'Julio', $fecha);
        }
        elseif(strpos($fecha, "August") !== false){
            $fecha = preg_replace('/August/', 'Agosto', $fecha);
        }
        elseif(strpos($fecha, "September") !== false){
            $fecha = preg_replace('/September/', 'Septiembre', $fecha);
        }
        elseif(strpos($fecha, "October") !== false){
            $fecha = preg_replace('/October/', 'Octubre', $fecha);
        }
        elseif(strpos($fecha, "November") !== false){
            $fecha = preg_replace('/November/', 'Noviembre', $fecha);
        }
        else{
            $fecha = preg_replace('/December/', 'Diciembre', $fecha);
        }

        return $fecha;
    }

    function seccionKeywords($clave){
        $this->viewBuilder()->layout('Front/categoria');

        //Recuperamos las noticias para la clave destacadas
        $noticias = $this->noticiasDestacadas($clave);
        $this->paginate = [
            'limit' => 16
        ];

        //recuperamos las mas leidas
        $parent = TableRegistry::get('Categorias')->findByCodigo('SECCIONES')->first();
        $mas_leidos = $this->_masLeidas(5, $parent->id);
        //recuperamos todos los portales
        $portales = $this->portalesActivos();
        //recuperamos el menu
        $menu = $this->_recuperarMenu();
        //recuperamos los portales en el menu
        $portales_menu = $this->portalesEnMenu();
        //recuperamos las publicidades
        $banners_300x250 = $this->_recuperarBanners('HOME', '300x250');
        $banners_160x600 = $this->_recuperarBanners('SECCION', '160x600');
        $banners_728x90 = $this->_recuperarBanners('SECCION', '728x90');
        //recuperamos los temas del dia
        $destacadas = $this->temasDelDia();

        //seteamos las variables en la vista
        $this->set('noticias_seccion', array_reverse($this->paginate($noticias)->toArray()));
        $this->set(compact('clave', 'mas_leidos', 'portales', 'menu', 'portales_menu',
                'banners_300x250', 'banners_160x600', 'banners_728x90', 'destacadas'));
    }

    function secciones($codigo = null){
        $this->viewBuilder()->layout('Front/categoria');

        $categoria = TableRegistry::get('Categorias')->findByCodigo($codigo)
                ->contain(['Parent', 'PalabrasClaves'])
                ->first();
        if(count($categoria->palabras_claves) > 0){
            $parent = TableRegistry::get('Categorias')->findByCodigo('SECCIONES')->first();
            $palabras_claves = array_map(create_function('$o', 'return $o["texto"];'), $categoria->palabras_claves);
            $query = $this->Articulos->findArticuloPorTag([], $palabras_claves, null, 0);

            $this->paginate = [
                'limit' => 16
            ];

            $seccion = $categoria;
        }
        else{
            $parent = TableRegistry::get('Categorias')->findByCodigo('SECCIONES')->first();
            $query = $this->Articulos->find();

            $this->paginate = [
                'contain' => [
                    'Categorias', 'Portales', 'Imagenes'
                ],
                'conditions' => [
                    'Articulos.habilitado',
                    'Categorias.categoria_id' => $parent->id,
                    'Categorias.codigo' => $codigo
                ],
                'fields' => [
                    'Articulos.id',
                    'Articulos.titulo',
                    'Articulos.descripcion',
                    'Articulos.publicado',
                    'Categorias.nombre',
                    'Categorias.codigo',
                    'Portales.nombre',
                    'Portales.codigo',
                ],
                'order' => ['Articulos.publicado' => 'DESC'],
                'limit' => 16
            ];

            $seccion = TableRegistry::get('Categorias')->findByCodigo($codigo)->first();
        }

        //recuperamos las mas leidas
        $mas_leidos = $this->_masLeidas(5, $parent->id);
        //recuperamos todos los portales
        $portales = $this->portalesActivos();
        //recuperamos el menu
        $menu = $this->_recuperarMenu();
        //recuperamos los portales en el menu
        $portales_menu = $this->portalesEnMenu();
        //recuperamos las publicidades
        $banners_300x250 = $this->_recuperarBanners('HOME', '300x250');
        $banners_160x600 = $this->_recuperarBanners('SECCION', '160x600');
        $banners_728x90 = $this->_recuperarBanners('SECCION', '728x90');
        //recuperamos los temas del dia
        $destacadas = $this->temasDelDia();

        //seteamos las variables en la vista
        $this->set('noticias_seccion', array_reverse($this->paginate($query)->toArray()));
        $this->set(compact('seccion', 'mas_leidos', 'portales', 'menu', 'portales_menu',
                'banners_300x250', 'banners_160x600', 'banners_728x90', 'destacadas'));
    }

    function portales($codigo = null){
        $this->viewBuilder()->layout('Front/portales');

        $portal = TableRegistry::get('Portales')->find()
                ->where(['Portales.codigo' => $codigo])->first();
        $articulos = $this->Articulos->find();
        $this->paginate = [
            'contain' => ['Categorias', 'Portales', 'Imagenes'],
            'order' => [
                'Articulos.publicado' => 'desc'
            ],
            'conditions' => [
                'Articulos.habilitado',
                'Articulos.portal_id' => $portal->id
            ],
            'fields' => [
                'Articulos.id',
                'Articulos.titulo',
                'Articulos.descripcion',
                'Articulos.publicado',
                'Categorias.nombre',
                'Categorias.codigo',
                'Portales.nombre',
                'Portales.codigo',
            ],
            'order' => ['Articulos.publicado' => 'DESC'],
            'limit' => 16
        ];

        //recuperamos las mas leidas
        $parent = TableRegistry::get('Categorias')->findByCodigo('SECCIONES')->first();
        $mas_leidos = $this->_masLeidas(5, $parent->id);
        //recuperamos todos los portales
        $portales = $this->portalesActivos();
        //recuperamos el menu
        $menu = $this->_recuperarMenu();
        //recuperamos los portales en el menu
        $portales_menu = $this->portalesEnMenu();
        //recuperamos las publicidades
        $banners_300x250 = $this->_recuperarBanners('HOME', '300x250');
        $banners_160x600 = $this->_recuperarBanners('SECCION', '160x600');
        $banners_728x90 = $this->_recuperarBanners('SECCION', '728x90');
        //recuperamos los temas del dia
        $destacadas = $this->temasDelDia();

        //seteamos las variables en la vista
        $this->set('noticias_seccion', array_reverse($this->paginate($articulos)->toArray()));
        $this->set(compact('portal', 'mas_leidos', 'portales', 'menu', 'portales_menu',
                'banners_300x250', 'banners_160x600', 'banners_728x90', 'destacadas'));
    }

    function revistas($codigo = null){
        $this->viewBuilder()->layout('Front/revistas');

        if($codigo == 'MCH'){
            $portal = TableRegistry::get('Categorias')->find()
                ->where(['Categorias.codigo' => $codigo])->first();

            $articulos = $this->Articulos->find();
            $this->paginate = [
                'contain' => [
                    'Categorias', 'Portales', 'Imagenes'
                ],
                'conditions' => [
                    'Articulos.habilitado',
                    'Articulos.categoria_id' => $portal->id
                ],
                'fields' => [
                    'Articulos.id',
                    'Articulos.titulo',
                    'Articulos.descripcion',
                    'Articulos.publicado',
                    'Categorias.nombre',
                    'Categorias.codigo',
                    'Portales.nombre',
                    'Portales.codigo'
                ],
                'order' => [
                    'Articulos.publicado' => 'DESC'
                ],
                'limit' => 20
            ];
        }
        else{
            $portal = TableRegistry::get('Portales')->find()
                ->where(['Portales.codigo' => $codigo])->first();

            $articulos = $this->Articulos->find();
            $this->paginate = [
                'contain' => [
                    'Categorias', 'Portales', 'Imagenes'
                ],
                'conditions' => [
                    'Articulos.habilitado',
                    'Articulos.categoria_id' => $portal->id
                ],
                'fields' => [
                    'Articulos.id',
                    'Articulos.titulo',
                    'Articulos.descripcion',
                    'Articulos.publicado',
                    'Categorias.nombre',
                    'Categorias.codigo',
                    'Portales.nombre',
                    'Portales.codigo'
                ],
                'order' => [
                    'Articulos.publicado' => 'DESC'
                ],
                'limit' => 16
            ];
        }

        //recuperamos las mas leidas
        $parent = TableRegistry::get('Categorias')->findByCodigo('SECCIONES')->first();
        $mas_leidos = $this->_masLeidas(5, $parent->id);
        //recuperamos todos los portales
        $portales = $this->portalesActivos();
        //recuperamos el menu
        $menu = $this->_recuperarMenu();
        //recuperamos los portales en el menu
        $portales_menu = $this->portalesEnMenu();
        //recuperamos las publicidades
        $banners_300x250 = $this->_recuperarBanners('HOME', '300x250');
        $banners_160x600 = $this->_recuperarBanners('SECCION', '160x600');
        $banners_728x90 = $this->_recuperarBanners('REVISTAS', '728x90');
        //recuperamos los temas del dia
        $destacadas = $this->temasDelDia();

        //seteamos las variables en la vista
        $this->set('noticias_seccion', array_reverse($this->paginate($articulos)->toArray()));
        $this->set(compact('portal', 'mas_leidos', 'portales', 'menu', 'portales_menu',
                'banners_300x250', 'banners_160x600', 'banners_728x90', 'destacadas'));
    }

    function radios($codigo){
        $this->viewBuilder()->layout('Front/radios');

        $tipo = explode('-', $codigo);
        $radios = TableRegistry::get('Canales')->find()
               ->where(['Canales.habilitado', 'Canales.tipo' => strtolower(end($tipo))])->toArray();

        //recuperamos las mas leidas
        $parent = TableRegistry::get('Categorias')->findByCodigo('SECCIONES')->first();
        $mas_leidos = $this->_masLeidas(5, $parent->id);
        //recuperamos todos los portales
        $portales = $this->portalesActivos();
        //recuperamos el menu
        $menu = $this->_recuperarMenu();
        //recuperamos los portales en el menu
        $portales_menu = $this->portalesEnMenu();
        //recuperamos las publicidades
        $banners_300x250 = $this->_recuperarBanners('HOME', '300x250');
        $banners_728x90 = $this->_recuperarBanners('REVISTAS', '728x90');
        //recuperamos los temas del dia
        $destacadas = $this->temasDelDia();

        //seteamos las variables en la vista
        $this->set('radios', $radios);
        $this->set(compact('mas_leidos', 'portales', 'menu', 'portales_menu',
                'banners_300x250', 'banners_728x90', 'destacadas'));
    }

    function reproductor(){
        $this->viewBuilder()->layout('Front/reproductor');
        $this->render(false);
    }

    function television(){
        $this->viewBuilder()->layout('Front/tv');

        $tvs = TableRegistry::get('Canales')->find()
                ->where(['Canales.habilitado', 'Canales.tipo' => "televisión"])->toArray();

        //recuperamos las mas leidas
        $parent = TableRegistry::get('Categorias')->findByCodigo('SECCIONES')->first();
        $mas_leidos = $this->_masLeidas(5, $parent->id);
        //recuperamos todos los portales
        $portales = $this->portalesActivos();
        //recuperamos el menu
        $menu = $this->_recuperarMenu();
        //recuperamos los portales en el menu
        $portales_menu = $this->portalesEnMenu();
        //recuperamos las publicidades
        $banners_300x250 = $this->_recuperarBanners('HOME', '300x250');
        $banners_728x90 = $this->_recuperarBanners('TV', '728x90');
        //recuperamos los temas del dia
        $destacadas = $this->temasDelDia();

        //seteamos las variables en la vista
        $this->set('tvs', $tvs);
        $this->set(compact('mas_leidos', 'portales', 'menu', 'portales_menu',
                'banners_300x250', 'banners_728x90', 'destacadas'));
    }

    function reproductorTv(){
        $this->viewBuilder()->layout('Front/reproductor_tv');
        $this->render(false);
    }

    function cartelera(){
        $categorias_table = TableRegistry::get('Categorias');
        $articulos_table = TableRegistry::get('Articulos');
        if(($noticias_en_cartelera = Cache::read('noticias_en_cartelera', 'large-15min')) === false) {
            $noticias_en_cartelera = [];
            //busco cada una de las categorias que estan en cartelera
            $en_cartelera = $categorias_table->categoriasEnCartelera();

            //Por cada categoria en la cartelera, obtenemos la ultima noticia
            foreach($en_cartelera as $categoria){
                //si es una categoria especial, recuperar todas las palabras claves y buscar la ultima noticia
                if(count($categoria->palabras_claves) > 0){
                    $palabras_claves = array_map(create_function('$o', 'return $o["texto"];'), $categoria->palabras_claves);
                    $query = $this->Articulos->findArticuloPorTag([], $palabras_claves, null, 1)->first();
                    array_push($noticias_en_cartelera, $query);
                }
                else{
                    $noticia = $articulos_table->findUltimaPorCategoria($categoria->id);
                    array_push($noticias_en_cartelera, $noticia);
                }
            }
            Cache::write('noticias_en_cartelera', $noticias_en_cartelera, 'large-15min');
        }
        else{
            $noticias_en_cartelera = Cache::read('noticias_en_cartelera', 'large-15min');
        }

        return $noticias_en_cartelera;
    }

    function especial(){
        $categorias_table = TableRegistry::get('Categorias');
        if(($en_especial = Cache::read('en_especial', 'large-15min')) === false) {
            $en_especial = $categorias_table->findEspecial();
            Cache::write('en_especial', $en_especial, 'large-15min');
        }
        else{
            $en_especial = Cache::read('en_especial', 'large-15min');
        }

        return $en_especial;
    }

    private function _masLeidas($limite, $parent){
        $articulos_table = TableRegistry::get('Articulos');
        if(($mas_leidos = Cache::read('mas_leidos', 'large-15min')) === false) {
            $mas_leidos = $articulos_table->findMasLeidos([], $parent, $limite)->toArray();
            Cache::write('mas_leidos', $mas_leidos, 'large-15min');
        }
        else{
            $mas_leidos = Cache::read('mas_leidos', 'large-15min');
        }

        return $mas_leidos;
    }

    public function _recuperarMenu(){
        if (($menu = Cache::read('menu_categorias', 'large-15min')) === false) {
            //debe llevar primero los (categoria_id -> null) que son los padres
            $categorias = TableRegistry::get('Categorias')->find('all')
                    ->contain(['Parent', 'Childs', 'PalabrasClaves'])
                    ->where([
                        'Categorias.en_menu' => 1,
                        'Categorias.categoria_id IS' => NULL
                        ])
                    ->order(['Categorias.posicion' => 'ASC'])->toArray();

            Cache::write('menu_categorias', $categorias, 'large-15min');

            return $categorias;
        }
        else{
            return $menu;
        }
    }

    public function _recuperarBanners($vista, $tipo){
        $banners = TableRegistry::get('Banners')
                ->find('all')
                ->select([
                    'Banners.banner_tipos_id',
                    'BannerTipos.ancho',
                    'BannerTipos.alto',
                    'Banners.filename',
                    'Banners.file_url',
                    'Banners.filename_mobile',
                    'Banners.file_mobile_url',
                    'Banners.href',
                    'Banners.mobile',
                    'BannerVista.posicion'])
                ->where([
                    'Vistas.codigo' => $vista,
                    'BannerTipos.nombre' => $tipo
                    ])
                ->order(['BannerVista.posicion' => 'ASC'])
                ->contain(['BannerTipos'])
                ->leftJoinWith('BannerVista.Vistas')
                ->toArray();

        return array_reverse($banners);
    }

    private function portalesActivos(){
        if(($portales = Cache::read('portales', 'large-15min')) === false) {
            $portales = TableRegistry::get('Portales')->find('all')
                    ->contain(['Imagenes'])
                    ->where(['Portales.en_menu'])
                    ->order(['Portales.nombre' => 'ASC'])->toArray();
            Cache::write('portales', $portales, 'large-15min');
        }
        else{
            $portales = Cache::read('portales', 'large-15min');
        }

        return $portales;
    }

    private function portalesEnMenu(){
        if(($portales_menu = Cache::read('portales_menu', 'large-15min')) === false) {
            $portales_menu = TableRegistry::get('Portales')->find('all')
                    ->select([
                        'Portales.nombre',
                        'Portales.codigo'
                        ])
                    ->where(['Portales.en_menu'])
                    ->order(['Portales.nombre' => 'ASC'])->toArray();
            Cache::write('portales_menu', $portales_menu, 'large-15min');
        }
        else{
            $portales_menu = Cache::read('portales_menu', 'large-15min');
        }

        return $portales_menu;
    }

    private function portadaDiarios(){
        $redes_controller = new RedesController;
        if (($portadas = Cache::read('portadas', 'large-15min')) === false) {
            $portadas = $redes_controller->obtenerPortadaDiarios();
            Cache::write('portadas', $portadas, 'large-15min');
        }
        else{
            $portadas = Cache::read('portadas', 'large-15min');
        }

        return $portadas;
    }

    private function temasDelDia(){
        $temas_dia_palabra_clave_controller = new TemasDiaPalabraClaveController;
        if (($destacadas = Cache::read('destacadas', 'large-15min')) === false) {
            $destacadas = $temas_dia_palabra_clave_controller->recuperarTemasDestacados();
            Cache::write('destacadas', $destacadas, 'large-15min');
        }
        else{
            $destacadas = Cache::read('destacadas', 'large-15min');
        }

        return $destacadas;
    }

    private function noticiasDestacadas($clave){
        $temas_dia_palabra_clave_controller = new TemasDiaPalabraClaveController;
        if (($noticias = Cache::read('noticias', 'large-15min')) === false) {
            $noticias = $temas_dia_palabra_clave_controller->recuperarNoticiasDestacadas($clave);
            //Cache::write('noticias', $noticias, 'large-15min');
        }
        else{
            $noticias = Cache::read('noticias', 'large-15min');
        }

        return $noticias;
    }

    public function generarListadoCanciones($path){
        $listado = [];




        return $listado;
    }

    public function filtrarListadoCanciones($listado){
        $listado_filtrado = [];



        return $listado_filtrado;
    }
}