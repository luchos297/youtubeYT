<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Cache\Cache;
use Cake\ORM\TableRegistry;

/**
 * Categorias Controller
 *
 * @property \App\Model\Table\CategoriasTable $Categorias
 */
class CategoriasController extends AppController
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
        $codigo = isset($this->request->query['codigo'])? $this->request->query['codigo'] : null;
        $supercategoria = isset($this->request->query['supercategoria'])? $this->request->query['supercategoria'] : null;
        
        $query = $this->Categorias->find();
        
        if($id != null && !empty($id)){
            $query = $query->where(['Categorias.id' => $id]);
        }
        if($nombre != null && !empty($nombre)){
            $query = $query->where(['Categorias.nombre LIKE' => '%'.trim($nombre).'%']);
        }
        if($codigo != null && !empty($codigo)){
            $query = $query->where(['Categorias.codigo LIKE' => '%'.trim($codigo).'%']);
        }
        
        if($supercategoria != null && !empty($supercategoria)){
            $query = $query->where(['Categorias.categoria_id' => $supercategoria ]);
        }      
        
        $this->paginate = [
            'contain' => ['Parent'],
            'order' => [
                'Categorias.nombre' => 'asc'
            ],
            'limit' => 50
        ];
        
        $categorias_list = $this->Categorias->find('list')
                ->where(['Categorias.categoria_id IS' => NULL])
                ->limit(200);
        $this->set('categorias_list', $categorias_list);
        $this->set('categorias', $this->paginate($query));
        $this->set('_serialize', ['categorias']);
    }

    /**
     * View method
     *
     * @param string|null $id Categoria id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $categoria = $this->Categorias->get($id, [
            'contain' => ['Parent', 'PalabrasClaves']
        ]);
        $this->set('categoria', $categoria);
        $this->set('_serialize', ['categoria']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $categoria = $this->Categorias->newEntity();
        if ($this->request->is('post')) {
            $categoria = $this->Categorias->patchEntity($categoria, $this->request->data);
            $categoria->creado = date("Y-m-d H:i:s");
            if ($this->Categorias->save($categoria)) {
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
                    $categoria = $this->Categorias->get($categoria->id);
                    $categoria->palabras_claves = $array_palabras_claves;
                    $this->Categorias->save($categoria);
                }
                Cache::delete('menu_categorias', 'large-15min');
                $this->Flash->success(__('La categoría ha sido guardada.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('La categoría no pudo ser guardada. Intente nuevamente.'));
            }
        }
        $categorias = $this->Categorias->Parent->find('list', ['limit' => 200]);
        $this->set(compact('categoria', 'categorias'));
        $this->set('_serialize', ['categoria']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Categoria id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $categoria = $this->Categorias->get($id,[
            'contain' => 'PalabrasClaves'
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $categoria = $this->Categorias->patchEntity($categoria, $this->request->data);
            $categoria->modificado = date("Y-m-d H:i:s");
            if ($this->Categorias->save($categoria)) {
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
                $categoria = $this->Categorias->get($categoria->id);
                $categoria->palabras_claves = $array_palabras_claves;
                $this->Categorias->save($categoria);
                
                Cache::delete('menu_categorias', 'large-15min');
                $this->Flash->success(__('La categoría ha sido guardada.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('La categoría no pudo ser guardada. Intente nuevamente.'));
            }
        }
        $categorias = $this->Categorias->Parent->find('list', ['limit' => 200]);
        $this->set(compact('categoria', 'categorias'));
        $this->set('_serialize', ['categoria']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Categoria id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $categoria = $this->Categorias->get($id);
        if ($this->Categorias->delete($categoria)) {
            Cache::delete('menu_categorias', 'large-15min');
            $this->Flash->success(__('La categoría ha sido borrada.'));
        } else {
            $this->Flash->error(__('La categoría no pudo ser borrada. Intente nuevamente.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    
    public function ordenarCategorias(){
        
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $this->response->disableCache();
            if(isset($this->request->data['menu'])){
                $menues = json_decode($this->request->data['menu']);
                foreach($menues as $key => $value){
                    $categoria = $this->Categorias->get($value->id);
                    $categoria->posicion = $key;
                    $subcategoria->categoria_id = NULL;
                    // Guardo los parent primero
                    $this->Categorias->save($categoria);
                    if(isset($value->children)){
                        foreach ($value->children as $key2 => $value2){
                            $subcategoria = $this->Categorias->get($value2->id);
                            $subcategoria->posicion = $key2;
                            $subcategoria->categoria_id = $categoria->id;
                            // Guardo las subcategorias
                            $this->Categorias->save($subcategoria);
                        }
                    }
                }
                
                Cache::delete('cake_large_15min_menu_categorias');
            }
        }
        
        /*
         * debe llevar primero los (categoria_id -> null) que son los padres
         */
        $categorias = $this->Categorias->find('all')
                ->contain(['Parent', 'Childs', 'PalabrasClaves'])
                ->where([
                    'Categorias.en_menu' => 1,
                    'Categorias.categoria_id IS' => NULL
                    ])
                ->order(['Categorias.posicion' => 'ASC'])
                ->toArray();
        
        Cache::write('menu_categorias', $categorias, 'large-15min');
        $this->set(compact('categorias'));
    }
}
