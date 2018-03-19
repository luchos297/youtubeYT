<?php
namespace App\Controller;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
/**
 * Banners Controller
 *
 * @property \App\Model\Table\BannersTable $Banners
 */
class BannersController extends AppController
{

    public function initialize() {
        parent::initialize();
        $this->loadComponent('RequestHandler');
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
        $descripcion = isset($this->request->query['descripcion'])? $this->request->query['descripcion'] : null;
        $banner_tipos = isset($this->request->query['banner_tipos'])? $this->request->query['banner_tipos'] : null;
        $view = isset($this->request->query['view'])? $this->request->query['view'] : null;
        
        //We search all the resources       
        $query = $this->Banners->find('all')
            ->contain(['BannerTipos', 'BannerVista', 'BannerVista.Vistas'])
            ->limit(50);        
        
        if($id != null && !empty($id)){
            $query = $query->where(['Banners.id' => $id]);
        }
        if($descripcion != null && !empty($descripcion)){
            $query = $query->where(['Banners.descripcion LIKE' => '%'.trim($descripcion).'%' ]);
        }
        if($banner_tipos != null && !empty($banner_tipos)){
            $query = $query->where(['Banners.banner_tipos_id' => $banner_tipos]);
        }
        if($view != null && !empty($view)){
            $query = $query->where(['Banners.BannerVista.vista_id' => $view]);
        }
        
        //We paginate all the resources with its related
        $query->paginate = [
            'contain' => ['BannerTipos', 'BannerVista'],
            'order' => [
                'Banners.creado' => 'desc'
            ],
            'limit' => 50
        ];
        
        //We search all the banner_tipos and views related
        $banners_tipo = $this->Banners->BannerTipos->find('list', ['limit' => 200]);
        $vistas = $this->Banners->BannerVista->Vistas->find('list', ['limit' => 200]);        
        
        //We set the resource in the view        
        $this->set('banners', $this->paginate($query));
        $this->set(compact('banners_tipo', 'vistas'));
        $this->set('_serialize', ['banners']);        
    }

    /**
     * View method
     *
     * @param string|null $id Banner id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        //We search the desired banner by its id
        $banner = $this->Banners->get($id, [
            'contain' => ['BannerTipos', 'BannerVista', 'BannerVista.Vistas']            
        ]);        
        //We set the resource in the view
        $this->set('banner', $banner);        
        $this->set('_serialize', ['banner']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->checkAuth();
        //We create the new entity that will content the new resource  
        $banner = $this->Banners->newEntity();        
        if ($this->request->is('post')) {
            //We set the own data from the request            
            $banner->descripcion = $this->request->data['descripcion'];
            $banner->banner_tipos_id = $this->request->data['banner_tipos_id'];
            $banner->mobile = $this->request->data['mobile'];
            $banner->href = $this->request->data['href'];                   
            $banner->creado = date("Y-m-d H:i:s");
            //Guardo la noticia
            if ($this->Banners->save($banner)) {                
                //Buscamos el banner_tipo asociado
                $banner_tipo = TableRegistry::get('BannerTipos')->find('all')                
                    ->where(['BannerTipos.id' => $this->request->data['banner_tipos_id']])->toArray();
                $banner_tipo = $banner_tipo[0];            
                $alto_banner_tipo = $banner_tipo->alto;
                $ancho_banner_tipo = $banner_tipo->ancho;
                
                if($this->request->data['filename']['name'] != ''){
                    //Verificamos que el el tamaño del SWF se corresponda con el del select                
                    $imagedata = getimagesize($this->request->data['filename']['tmp_name']);
                    $ancho_swf = $imagedata[0];
                    $alto_swf = $imagedata[1];                                
                    //Si coinciden las dimensiones
                    if($alto_banner_tipo == $alto_swf && $ancho_banner_tipo == $ancho_swf){
                        $banner->filename = $this->request->data['filename'];
                        $this->Banners->save($banner);
                    }
                    else{
                        $this->Flash->error(__('Las dimensiones de la imagen no corresponden con el tipo de publicidad.'));
                        return $this->redirect(['action' => 'index']);
                    }                    
                    //Verificamos que si viene una imagen mobile, este activado el checkbox
                    if(strpos($this->request->data['filename']['name'], "swf") && $banner->mobile == "1" && $this->request->data['filename_mobile']['name'] != ''){                
                        //Verificamos que el el tamaño del PNG se corresponda con el del select
                        $imagedata = getimagesize($this->request->data['filename_mobile']['tmp_name']);
                        $ancho_img = $imagedata[0];
                        $alto_img = $imagedata[1];                
                        //Si coinciden las dimensiones
                        if($alto_banner_tipo == $alto_img && $ancho_banner_tipo == $ancho_img){
                            $banner->filename_mobile = $this->request->data['filename_mobile'];
                            $this->Banners->save($banner);
                        }
                        else{
                            $this->Flash->error(__('Las dimensiones de la imagen mobile no corresponden con el tipo de publicidad.'));
                            return $this->redirect(['action' => 'index']);
                        }
                    }                    
                }
                else{
                    $this->Flash->error(__('La imagen no pudo ser guardada.'));
                    return $this->redirect(['action' => 'index']);
                }
            }
            return $this->redirect(['action' => 'edit', $banner->id]);
        }
        //We search all the banner_tipos and views related
        $banners_tipo = $this->Banners->BannerTipos->find('list', ['limit' => 200]);
        $vistas = $this->Banners->BannerVista->Vistas->find('list', ['limit' => 200]);
        $this->set(compact('banner', 'banners_tipo', 'vistas'));
        $this->set('_serialize', ['banner']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Banner id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->checkAuth();
        //We search the selected resource by its own id
        $banner = $this->Banners->get($id, [
            'contain' => ['BannerVista', 'BannerVista.Vistas', 'BannerTipos']
        ]);        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $dirname = WWW_ROOT . "img/images/banners/filename/" . $banner->file_url . "/" . $banner->filename;           
            $dirname_mobile = WWW_ROOT . "img/images/banners/filename_mobile/" . $banner->file_mobile_url . "/" . $banner->filename_mobile;           

            //We set the own data from the request
            $banner->descripcion = $this->request->data['descripcion'];
            $banner->banner_tipos_id = $this->request->data['banner_tipos_id'];
            $banner->href = $this->request->data['href']; 
            $banner->modificado = date("Y-m-d H:i:s");

            //Buscamos el banner_tipo asociado
            $banner_tipo = TableRegistry::get('BannerTipos')->find('all')                
                ->where(['BannerTipos.id' => $this->request->data['banner_tipos_id']])->toArray();
            $banner_tipo = $banner_tipo[0];
            $alto_banner_tipo = $banner_tipo->alto;
            $ancho_banner_tipo = $banner_tipo->ancho;

            //Verificamos que si agrego o no nuevas imagenes, se correspondan con las dimensiones del tipo
            if($this->request->data['filename']['name'] != ""){
                $imagedata_img = getimagesize($this->request->data['filename']['tmp_name']);
                $ancho_img = $imagedata_img[0];
                $alto_img = $imagedata_img[1];
                if($alto_banner_tipo != $alto_img && $ancho_banner_tipo != $alto_img){
                    $this->Flash->error(__('Las dimensiones de la imagen mobile no corresponden con el tipo seleccionado.'));
                    return $this->redirect(['action' => 'index']);
                }
                if($this->request->data['filename_mobile']['name'] != ""){
                    if($this->request->data['filename_mobile']['tmp_name'] != ""){
                        $imagedata_mobile = getimagesize($this->request->data['filename_mobile']['tmp_name']);
                        $ancho_mobile = $imagedata_mobile[0];
                        $alto_mobile = $imagedata_mobile[1];
                        if($alto_banner_tipo != $alto_mobile && $ancho_banner_tipo != $ancho_mobile){
                            $this->Flash->error(__('Las dimensiones de la imagen mobile no corresponden con el tipo seleccionado.'));
                            return $this->redirect(['action' => 'index']);
                        }
                    }
                }
            }
            else{
                $ancho_img = $banner->banner_tipo->ancho;
                $alto_img = $banner->banner_tipo->alto;
                if($alto_banner_tipo != $alto_img && $ancho_banner_tipo != $alto_img){
                    $this->Flash->error(__('Las dimensiones de la imagen mobile no corresponden con el tipo seleccionado.'));
                    return $this->redirect(['action' => 'index']);
                }
            }

            //We save the data
            if ($this->Banners->save($banner)) {
                //Verificamos si cambio el tipo de banner
                $tipo_id = $banner->banner_tipos_id;
                if($tipo_id != $this->request->data['banner_tipos_id']){
                    TableRegistry::get('BannerVista')->deleteAll(['banner_id' => $id]);
                }

                //Verificamos si las imagenes son nuevas o no
                if($this->request->data['filename']['name'] != "" && $this->request->data['filename']['name'] != $banner->filename){
                    //Verificamos que el el tamaño del SWF se corresponda con el del select                
                    $imagedata = getimagesize($this->request->data['filename']['tmp_name']);
                    $ancho_swf = $imagedata[0];
                    $alto_swf = $imagedata[1];
                    //Si coinciden las dimensiones
                    if($alto_banner_tipo == $alto_swf && $ancho_banner_tipo == $ancho_swf){
                        $banner->filename = $this->request->data['filename'];
                        $this->Banners->save($banner);
                        unlink($dirname);
                    }
                    else{
                        $this->Flash->error(__('Las dimensiones de la imagen no corresponden con el tipo de publicidad.'));
                        return $this->redirect(['action' => 'index']);
                    }
                    //Verificamos que si viene una imagen mobile, este activado el checkbox
                    if(strpos($this->request->data['filename']['name'], "swf") && $this->request->data['filename_mobile']['name'] != "" && $this->request->data['filename_mobile']['name'] != $banner->filename_mobile){
                        //Verificamos que el el tamaño del PNG se corresponda con el del select
                        $imagedata = getimagesize($this->request->data['filename_mobile']['tmp_name']);
                        $ancho_img = $imagedata[0];
                        $alto_img = $imagedata[1];
                        //Si coinciden las dimensiones
                        if($alto_banner_tipo == $alto_img && $ancho_banner_tipo == $ancho_img){
                            $banner->filename_mobile = $this->request->data['filename_mobile'];
                            $this->Banners->save($banner);
                            unlink($dirname_mobile);
                        }
                        else{
                            $this->Flash->error(__('Las dimensiones de la imagen mobile no corresponden con el tipo de publicidad.'));
                            return $this->redirect(['action' => 'index']);
                        }
                    }                                       
                }                
                $this->Flash->success(__('La publicidad se ha guardado correctamente.'));
            }            
            return $this->redirect(['action' => 'index']);
        }
        //We search all the banner_tipos and views related
        $banners_tipo = $this->Banners->BannerTipos->find('list', ['limit' => 200]);
        $vistas = $this->Banners->BannerVista->Vistas->find('list', ['limit' => 200]);
        $this->set(compact('banner', 'banners_tipo', 'vistas'));
        $this->set('_serialize', ['banner']);      
    }

    /**
     * Delete method
     *
     * @param string|null $id Banner id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->checkAuth();
        $this->request->allowMethod(['post', 'delete']);
        $banner = $this->Banners->get($id, [
            'contain' => ['BannerTipos', 'BannerVista', 'BannerVista.Vistas']            
        ]);        
        if ($this->Banners->delete($banner)) {
            $this->Flash->success(__('La publicidad ha sido borrada.'));
        } else {
            $this->Flash->error(__('La publicidad no pudo ser borrada. Intente nuevamente.'));
        }
        //We delete the resource storaged too        
        $dirname = WWW_ROOT . "img/images/banners/filename/" . $banner->file_url . "/" . $banner->filename;
        $dirname_mobile = WWW_ROOT . "img/images/banners/filename_mobile/" . $banner->file_mobile_url . "/" . $banner->filename_mobile;           
        array_map('unlink', glob("$dirname/*.*"));
        array_map('unlink', glob("$dirname_mobile/*.*"));
        return $this->redirect(['action' => 'index']);
    }
    
    public function actualizarVistaPosicion(){
        
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $this->response->disableCache();
            
            $banner_id = $this->request->data['id'];
            $vista_id = $this->request->data['vista'];
            $banner_tipo_id = $this->request->data['banner_tipo_id'];
            $posiciones = explode(',',$this->request->data['posiciones']);
            
            $coincidencias = [];
            $banner_vista_array = [];            
            
            foreach($posiciones as $posicion){
                
                if(TableRegistry::get('BannerVista')->find()
                        ->contain(['Banners'])
                        ->select(['id'])
                        ->where([
                            'posicion' => $posicion, 
                            'vista_id' => $vista_id,
                            'Banners.banner_tipos_id' => $banner_tipo_id])
                        ->first()
                        ){
                    $vista = TableRegistry::get('Vistas')->get($vista_id);
                    array_push($coincidencias, "Vista ".$vista->codigo." orden ".$posicion.": ya asignado.");
                }
                else{
                    $banner_vista = TableRegistry::get('BannerVista')->newEntity();             
                    $banner_vista->banner_id = $banner_id;  
                    $banner_vista->vista_id = $vista_id;
                    $banner_vista->posicion = $posicion;                                                                     
                    array_push($banner_vista_array, $banner_vista);
                }
            }
            
            if(count($coincidencias) == 0){
                $banner = $this->Banners->get($banner_id);
                $banner->banner_vista = $banner_vista_array;
                if ($this->Banners->save($banner)) {  
                    $response['status'] = ['success' => []];
                }
                else{
                    $response['status'] = ['error' => 'No se pudo actualizar.'];
                }
            }
            else{
                $response['status'] = ['error' => $coincidencias];                
            } 
            
            $this->response->body(json_encode($response));
            return $this->response;
        }
    }
    
    function borrarVistaPosicion(){
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $this->response->disableCache();
            
            $banner_id = $this->request->data['id'];
            $vista_codigo = trim($this->request->data['vista']);
            $posiciones = explode(',',trim($this->request->data['posiciones']));
            
            $coincidencias = [];
            $banner_vista_array = [];
            foreach($posiciones as $posicion){
                if($banner_vista = TableRegistry::get('BannerVista')->find()
                        ->contain(['Vistas'])
                        ->select(['id'])
                        ->where(['posicion' => $posicion, 'Vistas.codigo' => $vista_codigo])
                        ->first()){
                    array_push($banner_vista_array, $banner_vista->id);
                }
            } 
            if(TableRegistry::get('BannerVista')->deleteAll(['id IN' => $banner_vista_array])){
                $response['status'] = ['success' => []];
            }
            else{
                $response['status'] = ['error' => 'No se pudo borrar.'];
            }
            
            $this->response->body(json_encode($response));
            return $this->response;
        }
    }
}
