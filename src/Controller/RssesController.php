<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Rsses Controller
 *
 * @property \App\Model\Table\RssesTable $Rsses
 */
class RssesController extends AppController
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
        $portal = isset($this->request->query['portal'])? $this->request->query['portal'] : null;
        $categoria = isset($this->request->query['categoria'])? $this->request->query['categoria'] : null;
        
        $query = $this->Rsses->find();
        
        if($id != null && !empty($id)){
            $query = $query->where(['Rsses.id' => $id]);
        }
        if($categoria != null && !empty($categoria)){
            $query = $query->where(['Rsses.categoria_id' => $categoria]);
        }
        if($portal != null && !empty($portal)){
            $query = $query->where(['Rsses.portal_id' => $portal]);
        }        
        if($nombre != null && !empty($nombre)){
            $query = $query->where(['Rsses.nombre LIKE' => '%'.trim($nombre).'%' ]);
        }        
        
        $this->paginate = [
            'contain' => ['Categorias', 'Portales'],
            'order' => [
                'Rsses.nombre' => 'asc'
            ],
            'limit' => 50
        ];
        $portales = $this->Rsses->Portales->find('list')->limit(200);
        $categorias = $this->Rsses->Categorias->find('list')->limit(200);
        $this->set(compact('categorias', 'portales'));
        $this->set('rsses', $this->paginate($query));
        $this->set('_serialize', ['rsses']);
    }

    /**
     * View method
     *
     * @param string|null $id Rss id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $rss = $this->Rsses->get($id, [
            'contain' => ['Categorias', 'Portales']
        ]);
        $this->set('rss', $rss);
        $this->set('_serialize', ['rss']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $rss = $this->Rsses->newEntity();
        if ($this->request->is('post')) {
            $rss = $this->Rsses->patchEntity($rss, $this->request->data);
            $rss->creado = date("Y-m-d H:i:s");
            if ($this->Rsses->save($rss)) {
                $this->Flash->success(__('El rss ha sido guardado.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('El rss no pudo ser guardado. Intente nuevamente.'));
            }
        }
        $categorias = $this->Rsses->Categorias->find('list', ['limit' => 200]);
        $portales = $this->Rsses->Portales->find('list', ['limit' => 200]);
        $this->set(compact('rss', 'categorias', 'portales'));
        $this->set('_serialize', ['rss']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Rss id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $rss = $this->Rsses->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $rss = $this->Rsses->patchEntity($rss, $this->request->data);
            $rss->modificado = date("Y-m-d H:i:s");
            if ($this->Rsses->save($rss)) {
                $this->Flash->success(__('El rss ha sido guardado.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('El rss no pudo ser guardado. Intente nuevamente.'));
            }
        }
        $categorias = $this->Rsses->Categorias->find('list', ['limit' => 200]);
        $portales = $this->Rsses->Portales->find('list', ['limit' => 200]);
        $this->set(compact('rss', 'categorias', 'portales'));
        $this->set('_serialize', ['rss']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Rss id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $rss = $this->Rsses->get($id);
        if ($this->Rsses->delete($rss)) {
            $this->Flash->success(__('El rss ha sido borrado.'));
        } else {
            $this->Flash->error(__('El rss no pudo ser guardado. Intente nuevamente.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
