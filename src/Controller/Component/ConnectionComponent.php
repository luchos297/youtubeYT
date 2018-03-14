<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Datasource\ConnectionManager;
//use simple_html_dom;
/**
 * Description of ConnectionComponent
 *
 * @author Jesús Serna
 */
class ConnectionComponent extends Component {
    
    private static $connection;
    
    public function initialize(array $config)
    {
        $this->setConnection();
    }
    
    function getConnection(){
        return ConnectionComponent::$connection;
    }
    
    static function setConnection(){
        ConnectionComponent::$connection = ConnectionManager::get('default');
    }
}

?>