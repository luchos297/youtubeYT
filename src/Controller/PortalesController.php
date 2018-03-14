<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use ArrayObject;

/**
 * Portales Controller
 *
 * @property \App\Model\Table\PortalesTable $Portales
 */
class PortalesController extends AppController
{

    public function beforeFilter(Event $event)
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
        $codigo = isset($this->request->query['codigo'])? $this->request->query['codigo'] : null;

        $query = $this->Portales->find();

        if($id != null && !empty($id)){
            $query = $query->where(['Portales.id' => $id]);
        }
        if($nombre != null && !empty($nombre)){
            $query = $query->where(['Portales.nombre LIKE' => '%'.trim($nombre).'%']);
        }
        if($codigo != null && !empty($codigo)){
            $query = $query->where(['Portales.codigo LIKE' => '%'.trim($codigo).'%']);
        }

        $this->paginate = [
            'order' => [
                'Portales.nombre' => 'asc'
            ],
            'limit' => 50
        ];
        $this->set('portales', $this->paginate($query));
        $this->set('_serialize', ['portales']);
    }

    /**
     * View method
     *
     * @param string|null $id Portal id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $portal = $this->Portales->get($id, [
            'contain' => ['Imagenes']
        ]);
        $this->set('portal', $portal);
        $this->set('_serialize', ['portal']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $portal = $this->Portales->newEntity();
        $imagen = TableRegistry::get('Imagenes')->newEntity();
        if ($this->request->is('post')) {
            $portal = $this->Portales->patchEntity($portal, $this->request->data);
            $portal->creado = date("Y-m-d H:i:s");
            if ($this->Portales->save($portal)) {
                if($this->request->data['filename']['name']!= ''){
                    $imagen = TableRegistry::get('Imagenes')->newEntity();
                    $imagen = TableRegistry::get('Imagenes')->patchEntity($imagen, $this->request->data);
                    $imagen->creado = date("Y-m-d H:i:s");
                    $portal = $this->Portales->get($portal->id);
                    $portal->imagen = $imagen;
                    if ($this->Portales->save($portal)) {
                        //$this->UpdateAssociation->getConnection()->insert('imagen_articulo', ['articulo_id' => $articulo->id,'imagen_id' => $imagen->id]);
                    }
                    else{
                        $this->Flash->error(__('La imágen no pudo ser guardada.'));
                    }
                }
                $this->Flash->success(__('El portal ha sido guardado.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('El portal no pudo ser guardado. Intente nuevamente.'));
            }
        }
        $this->set(compact('portal'));
        $this->set('_serialize', ['portal']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Portal id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $portal = $this->Portales->get($id, [
            'contain' => ['Imagenes']
        ]);
        $imagen = TableRegistry::get('Imagenes')->newEntity();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $portal = $this->Portales->patchEntity($portal, $this->request->data);
            $portal->modificado = date("Y-m-d H:i:s");
            if ($this->Portales->save($portal)) {
                if($this->request->data['filename']['name']!= ''){
                    $imagen = TableRegistry::get('Imagenes')->newEntity();
                    $imagen = TableRegistry::get('Imagenes')->patchEntity($imagen, $this->request->data);
                    $imagen->creado = date("Y-m-d H:i:s");
                    $old_image = $portal->imagen;
                    $portal->imagen = $imagen;
                    if ($this->Portales->save($portal)) {
                        if($old_image != null){
                            TableRegistry::get('Imagenes')->delete($old_image);
                        }  
                    }
                    else{
                        $this->Flash->error(__('La imágen no pudo ser guardada.'));
                    }
                }
                $this->Flash->success(__('El portal ha sido guardado.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('El portal no pudo ser guardado. Intente nuevamente.'));
            }
        }
        $this->set(compact('portal'));
        $this->set('_serialize', ['portal']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Portal id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $portal = $this->Portales->get($id,[
            'contain' => ['Imagenes']
        ]);
        if ($this->Portales->delete($portal)) {
            $this->Flash->success(__('El portal ha sido borrado.'));
        } else {
            $this->Flash->error(__('El portal no pudo ser borrado. Intente nuevamente.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
