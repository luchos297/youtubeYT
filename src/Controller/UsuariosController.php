<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Usuarios Controller
 *
 * @property \App\Model\Table\UsuariosTable $Usuarios
 */
class UsuariosController extends AppController
{       
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['login','logout']);
        //$this->checkAuth();
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
        $email = isset($this->request->query['email'])? $this->request->query['email'] : null;
        
        $query = $this->Usuarios->find();
        
        if($id != null && !empty($id)){
            $query = $query->where(['Usuarios.id' => $id]);
        }
        if($email != null && !empty($email)){
            $query = $query->where(['Usuarios.email LIKE' => '%'.trim($email).'%']);
        }
        
        $this->paginate['limit'] = 50;
        $this->set('usuarios', $this->paginate($query));
        $this->set('_serialize', ['usuarios']);
    }

    /**
     * View method
     *
     * @param string|null $id Usuario id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->checkAuth();
        $usuario = $this->Usuarios->get($id, [
            'contain' => []
        ]);
        $this->set('usuario', $usuario);
        $this->set('_serialize', ['usuario']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->checkAuth();
        $usuario = $this->Usuarios->newEntity();
        if ($this->request->is('post')) {
            $usuario = $this->Usuarios->patchEntity($usuario, $this->request->data);
            $usuario->creado = date("Y-m-d H:i:s");
            if ($this->Usuarios->save($usuario)) {
                $this->Flash->success(__('El usuario ha sido guardado.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('El usuario no pudo ser guardado. Intente nuevamente.'));
            }
        }
        $this->set(compact('usuario'));
        $this->set('_serialize', ['usuario']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Usuario id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->checkAuth();
        $usuario = $this->Usuarios->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $usuario = $this->Usuarios->patchEntity($usuario, $this->request->data);
            $usuario->modificado = date("Y-m-d H:i:s");
            if ($this->Usuarios->save($usuario)) {
                $this->Flash->success(__('El usuario ha sido guardado.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('El usuario no pudo ser guardado. Intente nuevamente.'));
            }
        }
        $this->set(compact('usuario'));
        $this->set('_serialize', ['usuario']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Usuario id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->checkAuth();
        $this->request->allowMethod(['post', 'delete']);
        $usuario = $this->Usuarios->get($id);
        if ($this->Usuarios->delete($usuario)) {
            $this->Flash->success(__('El usuario ha sido borrado.'));
        } else {
            $this->Flash->error(__('El usuario no pudo ser borrado. Intente nuevamente.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    
    public function login()
    {        
        $this->viewBuilder()->layout('Cms/pages-login');
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error('Email o contraseña es incorrecta.');
        }
    }
    
    public function logout()
    {
        //$this->Flash->success('You are now logged out.');
        return $this->redirect($this->Auth->logout());
    }
    
    public function cambiarPassword(){
        $this->checkAuth();
        $usuario =$this->Usuarios->get($this->Auth->user('id'));
        if ($this->request->is(['patch', 'post', 'put'])) {
            $usuario = $this->Usuarios->patchEntity($usuario, [
                    'old_password'  => $this->request->data['old_password'],
                    'password'      => $this->request->data['password1'],
                    'password1'     => $this->request->data['password1'],
                    'password2'     => $this->request->data['password2']
                ],
                ['validate' => 'Password']
            );
            if ($this->Usuarios->save($usuario)) {
                $this->Flash->success('La contraseña ha sido guardada.');
                $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('El cambio no pudo ser guardado. Intente nuevamente.');
            }
        }
        $this->set('usuario',$usuario);
    }
}
