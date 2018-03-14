<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Core\Configure;

/**
 * Articulos Controller
 *
 * @property \App\Model\Table\ArticulosTable $Articulos
 */
class ArticulosController extends AppController
{

    public function initialize() {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['borradoProgramado']);
        $this->viewBuilder()->layout('Cms/default');
    }
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->checkAuth();
        $id = isset($this->request->query['id'])? $this->request->query['id'] : null;
        $titulo = isset($this->request->query['titulo'])? $this->request->query['titulo'] : null;
        $portal = isset($this->request->query['portal'])? $this->request->query['portal'] : null;
        $categoria = isset($this->request->query['categoria'])? $this->request->query['categoria'] : null;
        $fecha = isset($this->request->query['fecha'])? $this->request->query['fecha'] : null;

        $query = $this->Articulos->find();

        if($id != null && !empty($id)){
            $query = $query->where(['Articulos.id' => $id]);
        }
        if($categoria != null && !empty($categoria)){
            $query = $query->where(['Articulos.categoria_id' => $categoria]);
        }
        if($portal != null && !empty($portal)){
            $query = $query->where(['Articulos.portal_id' => $portal]);
        }

        if($titulo != null && !empty($titulo)){
            $query = $query->where(['Articulos.titulo LIKE' => '%'.trim($titulo).'%' ]);
        }

        if($fecha != null && !empty($fecha) && strtotime($fecha) != false){
            $query = $query->where(['Articulos.publicado LIKE' => '%'.date('Y-m-d', strtotime($fecha)).'%']);//new \DateTime('-10 days')]);/*date('d-m-Y', strtotime($fecha))]);*/
        }

        $this->paginate = [
            'contain' => ['Categorias', 'Portales'],
            'order' => [
                'Articulos.publicado' => 'desc'
            ],
            'limit' => 50
        ];
        $portales = $this->Articulos->Portales->find('list', ['limit' => 200]);
        $categorias = $this->Articulos->Categorias->find('list')
                ->where(['Categorias.categoria_id IS NOT' => NULL])
                ->limit(200);
        $this->set('articulos', $this->paginate($query));
        $this->set(compact('categorias', 'portales'));
        $this->set('_serialize', ['articulos']);
    }

    /**
     * View method
     *
     * @param string|null $id Articulo id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->checkAuth();
        $articulo = $this->Articulos->get($id, [
            'contain' => ['Categorias', 'Portales', 'Imagenes', 'PalabrasClaves']
        ]);

        $this->set('articulo', $articulo);
        $this->set('_serialize', ['articulo']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->checkAuth();
        $articulo = $this->Articulos->newEntity();
        if ($this->request->is('post')) {
            $articulo = $this->Articulos->patchEntity($articulo, $this->request->data);
            $articulo->creado = date("Y-m-d H:i:s");
            //Guardo la noticia
            if ($this->Articulos->save($articulo)) {
                $array_imagenes = [];
                if(!empty($this->request->data['filename'])){
                    foreach($this->request->data['filename'] as $imagen_a_guardar){
                        $imagen = TableRegistry::get('Imagenes')->newEntity();
                        $imagen = TableRegistry::get('Imagenes')->patchEntity($imagen, $this->request->data);
                        $filename = [
                            'error' => $imagen_a_guardar['error'],
                            'name' => $imagen_a_guardar['name'],
                            'size' => $imagen_a_guardar['size'],
                            'tmp_name' => $imagen_a_guardar['tmp_name'],
                            'type' => $imagen_a_guardar['type']
                        ];
                        $imagen->filename = $filename;
                        $imagen->creado = date("Y-m-d H:i:s");
                        array_push($array_imagenes, $imagen);
                    }
                }
                $this->Articulos->afterDelete(new Event('Model.Articulos'),$articulo, new \ArrayObject());
                $articulo->imagenes = $array_imagenes;
                if ($this->Articulos->save($articulo)) {
                }
                else{
                    $this->Flash->error(__('La imágen no pudo ser guardada.'));
                }
                if(!empty($this->request->data['palabras_claves'])){
                    $array_palabras_claves = [];
                    $tags = explode(',',$this->request->data['palabras_claves']);
                    foreach($tags as $tag){
                        $palabra_clave_existente = TableRegistry::get('PalabrasClaves')->findByTexto($tag)->first();
                        if($palabra_clave_existente){
                            $palabra_clave = $palabra_clave_existente;
                        }
                        else{
                            $palabra_clave = TableRegistry::get('PalabrasClaves')->newEntity();
                            $palabra_clave->texto = $tag;
                            $palabra_clave->creado = date("Y-m-d H:i:s");
                        }

                        array_push($array_palabras_claves,$palabra_clave);
                    }
                    $articulo = $this->Articulos->get($articulo->id);
                    $articulo->palabras_claves = $array_palabras_claves;
                    $this->Articulos->save($articulo);
                }

                $this->Flash->success(__('El artículo ha sido guardado.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('El artículo no pudo ser guardado. Intente nuevamente.'));
            }
        }
        $categorias = $this->Articulos->Categorias->find('list', ['limit' => 200]);
        $portales = $this->Articulos->Portales->find('list', ['limit' => 200]);
        $this->set(compact('articulo', 'categorias', 'portales'));
        $this->set('_serialize', ['articulo']);
    }
    /**
     * Edit method
     *
     * @param string|null $id Articulo id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->checkAuth();
        $articulo = $this->Articulos->get($id, [
            'contain' => ['Imagenes','PalabrasClaves']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $articulo = $this->Articulos->patchEntity($articulo, $this->request->data);
            $articulo->modificado = date("Y-m-d H:i:s");
            if ($this->Articulos->save($articulo)) {
                $array_imagenes = [];
                if(!empty($this->request->data['filename'])){
                    foreach($this->request->data['filename'] as $imagen_a_guardar){
                        $imagen = TableRegistry::get('Imagenes')->newEntity();
                        $imagen = TableRegistry::get('Imagenes')->patchEntity($imagen, $this->request->data);
                        $filename = [
                            'error' => $imagen_a_guardar['error'],
                            'name' => $imagen_a_guardar['name'],
                            'size' => $imagen_a_guardar['size'],
                            'tmp_name' => $imagen_a_guardar['tmp_name'],
                            'type' => $imagen_a_guardar['type']
                        ];
                        $imagen->filename = $filename;
                        $imagen->creado = date("Y-m-d H:i:s");
                        array_push($array_imagenes, $imagen);
                    }
                }
                $this->Articulos->afterDelete(new Event('Model.Articulos'),$articulo, new \ArrayObject());
                $articulo->imagenes = $array_imagenes;
                $this->Articulos->save($articulo);

                $array_palabras_claves = [];
                if(!empty($this->request->data['palabras_claves_'])){
                    $tags = explode(',',$this->request->data['palabras_claves_']);
                    foreach($tags as $tag){
                        $palabra_clave_existente = TableRegistry::get('PalabrasClaves')->findByTexto($tag)->first();
                        if($palabra_clave_existente){
                            $palabra_clave = $palabra_clave_existente;
                        }
                        else{
                            $palabra_clave = TableRegistry::get('PalabrasClaves')->newEntity();
                            $palabra_clave->texto = $tag;
                            $palabra_clave->creado = date("Y-m-d H:i:s");
                        }

                        array_push($array_palabras_claves,$palabra_clave);
                    }
                }
                $articulo = $this->Articulos->get($articulo->id);
                $articulo->palabras_claves = $array_palabras_claves;
                $this->Articulos->save($articulo);

                $this->Flash->success(__('El artículo ha sido guardado.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('El artículo no pudo ser guardado. Intente nuevamente.'));
            }
        }
        $categorias = $this->Articulos->Categorias->find('list', ['limit' => 200]);
        $portales = $this->Articulos->Portales->find('list', ['limit' => 200]);
        $imagenes = $this->Articulos->Imagenes->find('list', ['limit' => 200]);
        $this->set(compact('articulo', 'categorias', 'portales', 'imagenes'));
        $this->set('_serialize', ['articulo']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Articulo id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->checkAuth();
        $this->request->allowMethod(['post', 'delete']);
        $articulo = $this->Articulos->get($id, [
            'contain' => ['Imagenes']
        ]);

        //borramos las fotos fisicas y el directorio del disco
        if(count($articulo->imagenes) > 0){
            foreach($articulo->imagenes as $imagen){
                unlink('/var/www/webroot/'. Configure::read('path_imagen_notas') . $imagen->file_url . '/' . $imagen->filename);
                rmdir('/var/www/webroot/'. Configure::read('path_imagen_notas') . $imagen->file_url);
            }
            //borramos las referencias de las imagenes del articulo
            $this->Articulos->borrarAsociados($articulo->id);
        }

        if ($this->Articulos->delete($articulo)) {
            $this->Flash->success(__('El artículo ha sido borrado.'));
        }
        else {
            $this->Flash->error(__('El artículo no pudo ser borrado. Intente nuevamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    function borradoMasivo() {
        $this->checkAuth();
        $this->request->allowMethod(['post', 'delete']);
        if (isset($_POST['idsNoticiasDelete'])) {
            $idNoticiasDelete = explode('-', $_POST['idsNoticiasDelete']);

            //borramos las fotos fisicas y el directorio del disco
            foreach($idNoticiasDelete as $id){
                $articulo = $this->Articulos->get($id, [
                    'contain' => ['Imagenes']
                ]);

                //borramos las fotos fisicas y el directorio del disco
                if(count($articulo->imagenes) > 0){
                    foreach($articulo->imagenes as $imagen){
                        unlink('/var/www/webroot/'. Configure::read('path_imagen_notas') . $imagen->file_url . '/' . $imagen->filename);
                        rmdir('/var/www/webroot/'. Configure::read('path_imagen_notas') . $imagen->file_url);
                    }
                    //borramos las referencias de las imagenes del articulo
                    $this->Articulos->borrarAsociados($articulo->id);
                }
            }

            if($this->Articulos->deleteAll(['id IN' => $idNoticiasDelete])){
                $this->Flash->success(__('Los artículos han sido borrados.'));
            }
            else{
                $this->Flash->error(__('No se han podido borrar los artículos. Intente nuevamente.'));
            }

            //obtengo los datos get para reenviarlos y no perderlos
            //$query_string = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);
            //parse_str($query_string, $queries);
            //$this->redirect(array('controller' => 'admin', 'action' => 'ver_noticias', '?' => $queries));
        }
        return $this->redirect(['action' => 'index']);
    }

    function borradoProgramado() {
        $this->viewBuilder()->layout('cms');
        $imagenes = [];
        $peso = 0;

        //buscamos los articulos que sean mas viejos que la fecha establecida
        $articulos = $this->Articulos->find('all')
                ->select([
                    'Articulos.id',
                    'Articulos.creado'
                    ])
                //->where(['Articulos.creado >' => new \DateTime('-33 days')])
                ->where(['Articulos.creado <' => new \DateTime('-30 days')])
                ->contain([
                    'Imagenes' => function ($q) {
                            return $q
                            ->select([
                                'file_url', 'filename'
                                ]);
                            }])
                ->toArray();

        if(count($articulos) > 0){
            //borramos las fotos fisicas y el directorio del disco
            foreach($articulos as $articulo) {
                if(count($articulo->imagenes) > 0){
                    foreach($articulo->imagenes as $imagen){
                        array_push($imagenes, Configure::read('path_imagen_notas') . $imagen->file_url . '/' . $imagen->filename);
                        $peso += round((filesize('/var/www/webroot/' . Configure::read('path_imagen_notas') . $imagen->file_url . '/' . $imagen->filename) * 0.0009765625) * 0.0009765625, 2);
                        unlink('/var/www/webroot/'. Configure::read('path_imagen_notas') . $imagen->file_url . '/' . $imagen->filename);
                        rmdir('/var/www/webroot/'. Configure::read('path_imagen_notas') . $imagen->file_url);
                    }
                    //borramos las referencias de las imagenes del articulo
                    $this->Articulos->borrarAsociados($articulo->id);
                }
            }

            $ids = array_map(create_function('$o', 'return $o["id"];'), $articulos);

            //borramos el articulo y las referencias a las imagenes
            if(count($ids) > 0){
                if($this->Articulos->deleteAll(['id IN' => $ids])){
                    $result = 'Se ha borrado un total de ' . count($articulos) . ' artículos y ' . $peso . ' MB en ' . count($imagenes) . ' imagenes.';
                    $this->set('result', $result);
                }
                else{
                    $result = 'Ha ocurrido un error al borrar las noticias.';
                    $this->set('result', $result);
                }
            }
        }
    }
}
