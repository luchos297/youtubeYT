<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Imagenes Controller
 *
 * @property \App\Model\Table\ImagenesTable $Imagenes
 */
class ImagenesController extends AppController
{

    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        $this->checkAuth();
        $this->viewBuilder()->layout('Cms/default');
    }
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $id = isset($this->request->query['id'])? $this->request->query['id'] : null;
        $nombre = isset($this->request->query['nombre'])? $this->request->query['nombre'] : null;
        $path = isset($this->request->query['path'])? $this->request->query['path'] : null;
        
        $query = $this->Imagenes->find();
        
        if($id != null && !empty($id)){
            $query = $query->where(['Imagenes.id' => $id]);
        }
        if($nombre != null && !empty($nombre)){
            $query = $query->where(['Imagenes.filename LIKE' => '%'.trim($nombre).'%']);
        }
        if($path != null && !empty($path)){
            $query = $query->where(['Imagenes.file_url LIKE' => '%'.trim($path).'%']);
        }
        
        $this->paginate['limit'] = 50;
        $this->set('imagenes', $this->paginate($query));
        $this->set('_serialize', ['imagenes']);
    }

    /**
     * View method
     *
     * @param string|null $id Imagen id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $imagen = $this->Imagenes->get($id, [
            'contain' => ['Articulos']
        ]);
        $this->set('imagen', $imagen);
        $this->set('_serialize', ['imagen']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $imagen = $this->Imagenes->newEntity();
        if ($this->request->is('post')) {
            $imagen = $this->Imagenes->patchEntity($imagen, $this->request->data);
            $imagen->creado = date("Y-m-d H:i:s");
            if ($this->Imagenes->save($imagen)) {
                $this->Flash->success(__('La imágen ha sido guadada.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('La imágen no pudo ser guardada. Intente nuevamente.'));
            }
        }
        $articulos = $this->Imagenes->Articulos->find('list', ['limit' => 200]);
        $this->set(compact('imagen', 'articulos'));
        $this->set('_serialize', ['imagen']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Imagen id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $imagen = $this->Imagenes->get($id, [
            'contain' => ['Articulos']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $imagen = $this->Imagenes->patchEntity($imagen, $this->request->data);
            $imagen->modificado = date("Y-m-d H:i:s");
            if ($this->Imagenes->save($imagen)) {
                $this->Flash->success(__('La imágen ha sido guardada.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('La imágen no pudo ser guardada. Intente nuevamente.'));
            }
        }
        $articulos = $this->Imagenes->Articulos->find('list', ['limit' => 200]);
        $this->set(compact('imagen', 'articulos'));
        $this->set('_serialize', ['imagen']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Imagen id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $imagen = $this->Imagenes->get($id);
        if ($this->Imagenes->delete($imagen)) {
            $this->Flash->success(__('La imagen ha sido borrada.'));
        } else {
            $this->Flash->error(__('La imágen no pudo ser borrada. Intente nuevamente.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
