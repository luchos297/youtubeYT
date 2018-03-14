<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Vistas Controller
 *
 * @property \App\Model\Table\VistasTable $Vistas
 */
class VistasController extends AppController
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
        $vistas = $this->paginate($this->Vistas);

        $this->set(compact('vistas'));
        $this->set('_serialize', ['vistas']);
    }

    /**
     * View method
     *
     * @param string|null $id Vista id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        //We search the desired view by its id
        $vista = $this->Vistas->get($id, [
            'contain' => ['BannerVista']
        ]);
        //We set the resource in the view
        $this->set('vista', $vista);
        $this->set('_serialize', ['vista']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        //We create the new entity that will content the new resource
        $vista = $this->Vistas->newEntity();
        if ($this->request->is('post')) {
            $vista = $this->Vistas->patchEntity($vista, $this->request->data);
            if ($this->Vistas->save($vista)) {
                $this->Flash->success(__('The vista has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The vista could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('vista'));
        $this->set('_serialize', ['vista']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Vista id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $vista = $this->Vistas->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $vista = $this->Vistas->patchEntity($vista, $this->request->data);
            if ($this->Vistas->save($vista)) {
                $this->Flash->success(__('The vista has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The vista could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('vista'));
        $this->set('_serialize', ['vista']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Vista id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $vista = $this->Vistas->get($id);
        if ($this->Vistas->delete($vista)) {
            $this->Flash->success(__('The vista has been deleted.'));
        } else {
            $this->Flash->error(__('The vista could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
