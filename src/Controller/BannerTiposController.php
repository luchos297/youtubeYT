<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * BannerTipos Controller
 *
 * @property \App\Model\Table\BannerTiposTable $BannerTipos
 */
class BannerTiposController extends AppController
{

    public function initialize() {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);        
        $this->viewBuilder()->layout('Cms/default');
    }
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $id = isset($this->request->query['id'])? $this->request->query['id'] : null;
        $nombre = isset($this->request->query['nombre'])? $this->request->query['nombre'] : null;
        $alto = isset($this->request->query['alto'])? $this->request->query['alto'] : null;
        $ancho = isset($this->request->query['ancho'])? $this->request->query['ancho'] : null;
             
        //We search all the resources
        $query = $this->BannerTipos->find();
        
        if($id != null && !empty($id)){
            $query = $query->where(['BannerTipos.id' => $id]);
        }
        if($nombre != null && !empty($nombre)){
            $query = $query->where(['BannerTipos.nombre LIKE' => '%'.trim($nombre).'%' ]);
        }
        if($alto != null && !empty($alto)){
            $query = $query->where(['BannerTipos.alto LIKE' => '%'.trim($alto).'%' ]);
        }
        if($ancho != null && !empty($ancho)){
            $query = $query->where(['BannerTipos.ancho' => $ancho]);
        }
        
        //We paginate all the resources
        $this->paginate = [
            'order' => [
                'BannerTipos.creado' => 'asc'
            ],
            'limit' => 50
        ];
               
        //We set the resource in the view        
        $this->set('banners_tipo', $this->paginate($query));        
        $this->set('_serialize', ['banners_tipo']);
    }

    /**
     * View method
     *
     * @param string|null $id Banner Tipo id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        //We search the desired resource by its id
        $bannerTipo = $this->BannerTipos->get($id, [
            'contain' => []
        ]);
        //We set the resource in the view
        $this->set('bannerTipo', $bannerTipo);
        $this->set('_serialize', ['bannerTipo']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {        
        //We create the new entity that will content the new resource
        $banner_tipo = $this->BannerTipos->newEntity();
        //If the type is 'post'
        if ($this->request->is('post')) {
            //We set the resource on new entity i just create it
            $banner_tipo = $this->BannerTipos->patchEntity($banner_tipo, $this->request->data);
            //We set the create date
            $banner_tipo->creado = date("Y-m-d H:i:s");
            //We save the data
            if ($this->BannerTipos->save($banner_tipo)) {            
                //If it was succeeded
                $this->Flash->success(__('El tipo de publicidad ha sido guardado.'));
                return $this->redirect(['action' => 'index']);
            } else {
                //If it wasn't
                $this->Flash->error(__('El tipo de publicidad no pudo ser guardado. Intente nuevamente.'));
            }
        }
        //We set the resource in the view
        $this->set(compact('banner_tipo'));
        $this->set('_serialize', ['banner_tipo']);        
    }

    /**
     * Edit method
     *
     * @param string|null $id Banner Tipo id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->checkAuth();
        //We search the selected resource by its own id
        $banner_tipo = $this->BannerTipos->get($id);
        //If the type is 'patch', 'post' or 'put'
        if ($this->request->is(['patch', 'post', 'put'])) {
            //We set the resource on new entity i just create it
            $banner_tipo = $this->BannerTipos->patchEntity($banner_tipo, $this->request->data);
            //We set the modified date
            $banner_tipo->modificado = date("Y-m-d H:i:s");
            //We save the data
            if ($this->BannerTipos->save($banner_tipo)) {
                //If it was succeeded
                $this->Flash->success(__('El tipo de publicidad ha sido guardado.'));
                return $this->redirect(['action' => 'index']);
            } else {
                //If it wasn't
                $this->Flash->error(__('El tipo de publicidad no pudo ser guardado. Intente nuevamente.'));
            }
        }
        //We set the resource in the view
        $this->set(compact('banner_tipo'));
        $this->set('_serialize', ['banner_tipo']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Banner Tipo id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        //If the type is 'post' or 'delete'
        $this->request->allowMethod(['post', 'delete']);
        //We search the selected resource by its own id
        $bannerTipo = $this->BannerTipos->get($id);
        //We delete the data
        if ($this->BannerTipos->delete($bannerTipo)) {
            //If it was succeeded
            $this->Flash->success(__('El tipo de publicidad ha sido borrado.'));
        } else {
            //If it wasn't
            $this->Flash->error(__('El tipo de publicidad no pudo ser borrado. Intente nuevamente.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
