<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * PalabrasClaves Controller
 *
 * @property \App\Model\Table\PalabrasClavesTable $PalabrasClaves
 */
class PalabrasClavesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('palabrasClaves', $this->paginate($this->PalabrasClaves));
        $this->set('_serialize', ['palabrasClaves']);
    }

    /**
     * View method
     *
     * @param string|null $id Palabras Clave id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $palabrasClave = $this->PalabrasClaves->get($id, [
            'contain' => ['Articulos', 'ArticuloPalabraClave']
        ]);
        $this->set('palabrasClave', $palabrasClave);
        $this->set('_serialize', ['palabrasClave']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $palabrasClave = $this->PalabrasClaves->newEntity();
        if ($this->request->is('post')) {
            $palabrasClave = $this->PalabrasClaves->patchEntity($palabrasClave, $this->request->data);
            if ($this->PalabrasClaves->save($palabrasClave)) {
                $this->Flash->success(__('The palabras clave has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The palabras clave could not be saved. Please, try again.'));
            }
        }
        $articulos = $this->PalabrasClaves->Articulos->find('list', ['limit' => 200]);
        $this->set(compact('palabrasClave', 'articulos'));
        $this->set('_serialize', ['palabrasClave']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Palabras Clave id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $palabrasClave = $this->PalabrasClaves->get($id, [
            'contain' => ['Articulos']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $palabrasClave = $this->PalabrasClaves->patchEntity($palabrasClave, $this->request->data);
            if ($this->PalabrasClaves->save($palabrasClave)) {
                $this->Flash->success(__('The palabras clave has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The palabras clave could not be saved. Please, try again.'));
            }
        }
        $articulos = $this->PalabrasClaves->Articulos->find('list', ['limit' => 200]);
        $this->set(compact('palabrasClave', 'articulos'));
        $this->set('_serialize', ['palabrasClave']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Palabras Clave id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $palabrasClave = $this->PalabrasClaves->get($id);
        if ($this->PalabrasClaves->delete($palabrasClave)) {
            $this->Flash->success(__('The palabras clave has been deleted.'));
        } else {
            $this->Flash->error(__('The palabras clave could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
