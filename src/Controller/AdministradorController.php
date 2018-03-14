<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdministradorController
 *
 * @author Jesús
 */
class AdministradorController extends AppController{
    //put your code here
    
    function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->checkAuth();
        $this->viewBuilder()->layout('Cms/default');
    }  
    
    public function index(){ 
        $this->redirect(array('controller' => 'articulos', 'action' => 'index'));
    }
}
