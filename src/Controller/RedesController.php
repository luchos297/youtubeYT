<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\ORM\TableRegistry;
use Cake\Cache\Cache;
use Cake\Utility\Inflector;
use Cake\Mailer\Email;
use simple_html_dom;
/**
 * Rsses Controller
 *
 * @property \App\Model\Table\RedesTable $Redes
 */
class RedesController extends AppController{

    public function initialize() {
        parent::initialize();
        $this->loadComponent('BaseCrawler');
    }
    
    public function beforeFilter(\Cake\Event\Event $event){
        parent::beforeFilter($event);
        $this->Auth->allow(['socialFacebook', 'socialTwitter', 'socialGPlus', 'publicarEnFacebook', 'publicarEnTwitter', 'obtenerNoticiasFechaErronea', 'generarPortadaDiarios']);
    }

    function socialFacebook(){
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $fb = new \Facebook\Facebook([
                'app_id' => '1624947381103552',
                'app_secret' => 'a58d1b25560142bc3bb5804a00490961',
                'default_graph_version' => 'v2.5',
            ]);

            $access = 'CAAXF4Ytt68ABABMU1tSQWtCsu0XDZCVZBZAZBq8CYKUIuRZCRmmQrxddylDA6vlNFy6C9ZBcBiqKZAzvWqeAli1fePgEdhzBk3gtApJlF4CORHdZBpleMfAZB5nc4KXWv3lHTdaa1oQZARwSZBRpxCJQ8QjmLZAjVPDD0X1uxw7gi1QkqcOvfW3dVVrZC';

            try {
                // Get the Facebook\GraphNodes\GraphUser object for the current user.
                // If you provided a 'default_access_token', the '{access-token}' is optional.
                $this->response->disableCache();
                $response = $fb->get('252361931473629?fields=id,name,likes', $access);
                $this->response->body($response->getBody());
                return $this->response;
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
        }
        else{
            $this->response->body('-');
            return $this->response;
        }
    }

    function socialTwitter(){ 
        $this->autoRender = false;

        if ($this->request->is('ajax')) {

            $settings = [
                'oauth_access_token'        => "4288014353-4I8GWUhOQygcDl3eHgIxz7APDCrzL1DfuM7tW8X",
                'oauth_access_token_secret' => "J1ZQ6nkEt9epwaOYHNYH4FRipIfoo3UOr4nQBoFs9kWWN",
                'consumer_key'              => "jyvFFXwK3y2U8O7c2cbzxnCEf",
                'consumer_secret'           => "vIJGwKdN2ZeigR93LLUZF6fiOpWNi3bICdMx0iZlYSe0ueON3d"
            ];

            $url = 'https://api.twitter.com/1.1/followers/ids.json';
            $getfield = '?screen_name=vista_medios';
            $requestMethod = 'GET';

            $twitter = new \TwitterAPIExchange($settings); 
            if (($followers = Cache::read('followers', 'short')) === false) {
                $followers = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();                
                Cache::write('followers', $followers, 'short');
                $followers = json_decode($followers, true);
            }
            else{
                $followers = Cache::read('followers', 'short');
                $followers = json_decode($followers, true);
            }

            if(isset($followers['ids'])){
                $this->response->body(count($followers['ids']));
            }
            else{
                $this->response->body('X');
            }
            return $this->response;
        }
        else{
            $this->response->body('-');
            return $this->response;
        }
    }

    function socialGPlus(){

        $this->autoRender = false;

        if ($this->request->is('ajax')) {
            $content =  file_get_contents("https://www.googleapis.com/plus/v1/people/107350038594612955632?key=AIzaSyD8x5MeyRS4JjFk1qNWoyt1L9pBF_EWalU");
            $data = json_decode($content, true);

            $this->response->disableCache();
            $this->response->body($data['circledByCount']);
            return $this->response;
        }
        else{
            $this->response->body('-');
            return $this->response;
        }
        //AIzaSyD8x5MeyRS4JjFk1qNWoyt1L9pBF_EWalU

        //oauth
        //id cliente: 280263065595-p0m69d0k7num9socn3ggbkhu00avqs16.apps.googleusercontent.com
        //secreto cliente: 9UI5WGPuyAl5c4D3PaocZBo3
    }

    function publicarEnFacebook(){
        set_time_limit(0);
        $this->autoRender = false;

        $fb = new \Facebook\Facebook([
                'app_id' => '1624947381103552',
                'app_secret' => 'a58d1b25560142bc3bb5804a00490961',
                'default_graph_version' => 'v2.5',
            ]);

        //sería un token que no expira
        //$token = 'CAAXF4Ytt68ABADq31Kf6LmuoHEbRZCDgwUzjzu6xxiKUvlD2BM3tjfsbECCaOfZAdM8LhZBfJ3Bu5oIEXCrv1P3gG9oXZB7H1MVQ8M7w6pZB31ZBrxVNSPrNqwsaepdaoJ8Azlq3DTtZAIGIi32fNsIHvsUGxzQvxdpieZBHZA58QzulNB2xcQht3nmZBpfYKNGdIZD';
        //$token = 'CAAXF4Ytt68ABAFSZCzFSk1w0sDsmuOAYrIiX691DCYiMrZB7u3fpW8ozTOrrJsOHywiaAL71n84oSrIm7UgIpq0bVNqjLtctZBEhv44YFYBCr3CivYJ6Xz4BIoUNd0lq5ctVBX4EUT409STsdFWtD8hl1c4uWq22nSmAS2SsZC2kLTqqSYuE1TwIrQcSLIYZD';
        $token = 'CAAXF4Ytt68ABAIZAD3YoUpxaCswKzLZBx2bQNJQCdp3yttJ4OGnveDbLLwdWLRPVpl5dgifeZBZA8wZAponHuD67mxFuHgAUYFxTZCUVlGiTEcA2PAC4hFxnih125srNuPqnFnDsCCskJlzY9c4ZAZAYmWDAWEsHKyAxOYhRvLojMQ8CWLF4llHqrFxvR1RMR3oZD';

        //buscamos las ultimas noticias para publicar
        $fecha = new \DateTime('-1 days');
        $categoria = TableRegistry::get('Categorias')->findByCodigo('SECCIONES')->first();        
        if(!is_null($categoria)){
            $articulos = TableRegistry::get('Articulos')
                    ->findArticulosAPublicar([], $fecha, $categoria->id, 1)
                    ->toArray();

            //publicamos cada noticia en FB
            echo "Publish OK!";
            echo '<pre>';

            foreach($articulos as $articulo){

                $linkData = [
                    'link' => \Cake\Core\Configure::read('dominio') . "noticias/articulo/" . $articulo->id . '/ ' . Inflector::slug(strtolower($articulo->titulo)),
                    ];

                try {
                    // Returns a Facebook\FacebookResponse object
                    $response = $fb->post('/252361931473629/feed', $linkData, $token);  

                    //actualizamos el estado de publicacion de la noticia
                    $articulo->publicado_fb = true;
                    TableRegistry::get('Articulos')->save($articulo);

                    echo "ID: " . $articulo->id . " -> " . $articulo->titulo . "\n";
                }
                catch(\Facebook\Exceptions\FacebookResponseException $e) {
                    echo 'Graph returned an error: ' . $e->getMessage();
                    exit;
                }
                catch(\Facebook\Exceptions\FacebookSDKException $e) {
                    echo 'Facebook SDK returned an error: ' . $e->getMessage();
                    exit;
                }
            }

            echo '</pre>';
        }
    }

    function publicarEnTwitter(){
        set_time_limit(0);
        $this->autoRender = false;

        $parent = TableRegistry::get('Categorias')->findByCodigo('SECCIONES')->first();
        $articulos = TableRegistry::get('Articulos')
                ->findArticulosAPublicar([],$parent->id,3)
                ->toArray();

        $settings = [
            'oauth_access_token'        => "4288014353-4I8GWUhOQygcDl3eHgIxz7APDCrzL1DfuM7tW8X",
            'oauth_access_token_secret' => "J1ZQ6nkEt9epwaOYHNYH4FRipIfoo3UOr4nQBoFs9kWWN",
            'consumer_key'              => "jyvFFXwK3y2U8O7c2cbzxnCEf",
            'consumer_secret'           => "vIJGwKdN2ZeigR93LLUZF6fiOpWNi3bICdMx0iZlYSe0ueON3d"
        ];


        $url = 'https://api.twitter.com/1.1/statuses/update.json';
        $requestMethod = 'POST';
        $twitter = new \TwitterAPIExchange($settings); 

        foreach($articulos as $articulo){
            $postfields = [
                'status' => $articulo->titulo." ".\Cake\Core\Configure::read('dominio')."noticias/articulo/".$articulo->id,
                'via' => '@' . \Cake\Core\Configure::read('usuario_twitter')
            ];

            $twitter->buildOauth($url, $requestMethod)
                    ->setPostfields($postfields)
                    ->performRequest();
        }
    }

    function obtenerNoticiasFechaErronea(){
        $this->autoRender = false;
        $ids = '';
        $noticias = [];
        //obtenemos la fecha de mañana porque algunas noticias tienen la fecha adelantada
        $fecha_actual = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s")) + (24*3600*1));
        //obtenemos las noticias con la fecha mayor a la actual
        $noticias_erroneas = TableRegistry::get('Articulos')->find('all')                
                ->where(['Articulos.publicado >' => $fecha_actual])
                ->contain(['Portales'])
                ->limit(500)->toArray();
        if(!is_null($noticias_erroneas) && count($noticias_erroneas) > 0){
            //para cada noticia obtenemos el ID y lo agregamos al mensaje
            foreach($noticias_erroneas as $noticia){
                $ids .= "- Noticia ID: " . $noticia->id . ",\n";
                $noc = ['id' => $noticia->id,
                        'titulo' => $noticia->titulo,
                        'portal' => $noticia->portal->codigo
                    ];
                array_push($noticias, $noc);
            }
            //definimos la estructura base del mensaje
            $message = "Revisar la fecha de las siguientes noticias:\n" . $ids;
            //creamos el email, seteamos los ids encontrados y enviamos el email

            $email = new Email();
            Email::configTransport('gmail', [
                'host' => 'ssl://smtp.gmail.com',
                'port' => 465,
                'username' => 'luchomza@gmail.com',
                'password' => 'Leonelaaa3086',
                'className' => 'Smtp'
                ]);

            try {
                $email->from(['luchomza@gmail.com' => 'VistaMedios'])
                      ->to('lbuttazzoni@aconcaguasf.com.ar')
                      ->subject('Noticias con fecha errónea')
                      ->transport('gmail')
                      ->send($message);
            }
            catch(Exception $e){
                echo 'Exception : ',  $e->getMessage(), "\n";
            }
        }
        $this->set('noticias', $noticias);
        $this->render('obtener_noticias_fecha_erronea');
    }

    public function generarPortadaDiarios(){
        $portadas = [];
        $path_imagenes_portada = Configure::read('path_imagen_portadas') . '*';

        //buscamos todos los portales
        $portales = TableRegistry::get('Portales')->find('all')
            ->where(['Portales.en_portada'])
            ->order(['Portales.codigo' => 'ASC'])->toArray();

        //guardamos la foto para cada portal
        foreach($portales as $portal){
            $flag = false;
            if($portal->url_impresa !== ""){
                $portada_url = $this->getScreenshotBySite($portal);
            }
            else{
                //$portada_url = "http://res.cloudinary.com/getscreenshots/image/upload/v1469113277/otzx3otcx7ayiuuweysq.jpg";
                $portada_url = $this->getScreenshotByAPI($portal);
                $flag = true;
            }
            if($portada_url != ""){
                //Si existe, la borramos y guardamos la nueva
                if($this->estaEnCarpetaPortada($path_imagenes_portada, $portal->codigo) == true){
                    $this->borrarImagenPortada($path_imagenes_portada, $portal->codigo);
                }
                $nombre = Configure::read('path_imagen_portadas') . $portal->codigo . '-' . date("Y-m-d") . '.jpg';
                //guardamos en disco la imagen
                $this->guardarPortada($nombre, $portada_url);
                //redimensionamos la imagen para soportar el tamaño por defecto
                if($flag == true){
                    $this->redimensionarPortada($nombre);
                }
                $portadas[] = ['portal' => $portal->nombre, 'url' => Configure::read('dominio') . $nombre];
            }
        }

        $this->set('portadas', $portadas);
        $this->render('generar_portada_diarios');
    }

    public function estaEnCarpetaPortada($path, $nombre){
        $valor = false;

        foreach(glob($path) as $archivo){
            if(strpos($archivo, $nombre)){
                $valor = true;
            }
        }

        return $valor;
    }

    public function borrarImagenPortada($path, $nombre){
        foreach(glob($path) as $archivo){
            if(strpos($archivo, $nombre)){
                unlink($archivo);
            }
        }
    }

    public function getScreenshotBySite($portal){
        $portada_url = '';

        $state = $this->BaseCrawler->getStateHeaderXml(explode('ar', $portal->url_impresa)[0]);
        if(!is_null($state['ok']) && strpos($state['state'], "200 OK") !== false){
            try{
                @$this->BaseCrawler->setHtmlDomFromString($portal->url_impresa, $this->BaseCrawler->getStreamContext());
                if($this->BaseCrawler->html){
                    $data = $this->BaseCrawler->html->find('.frontPageImage', 0);
                    if(!is_null($data)){
                        $portada_url = $data->find('img', 0)->attr['src'];
                    }
                }
            }
            catch(Exception $e){}
        }

        return $portada_url;
    }

    public function getScreenshotByAPI($portal){
        $screenshot_url = '';

        if(!is_null($portal)){
            //generamos la url del portal
            $data = "url=" . $portal->url;
            //obtenemos el ID del screenshot
            $screenshot_id = $this->getScreenshotID("http://getscreenshots.io/api/ss", $data);
            //obtenemos la URL del screenshot
            $screenshot_url = $this->getScreenshotURL($screenshot_id);
        }

        return $screenshot_url;
    }

    public function getScreenshotID($url, $data){
        $screenshot_id = '';

        if(!is_null($url) && !is_null($data)){
            //creamos un nuevo cURL resource
            $resource = curl_init($url);
            //seteamos los parametros y ejecutamos el comando
            curl_setopt($resource, CURLOPT_POST, 1);
            curl_setopt($resource, CURLOPT_POSTFIELDS, $data);
            curl_setopt($resource, CURLOPT_FOLLOWLOCATION, 1); // obey redirects
            curl_setopt($resource, CURLOPT_HEADER, 0);  // No HTTP headers
            curl_setopt($resource, CURLOPT_RETURNTRANSFER, 1);  // return the data
            //ejecutamos el post
            $result = curl_exec($resource);
            //cerramos la coneccion
            curl_close($resource);
            //obtenemos la URL con el ID
            $screenshot_decoded = json_decode($result)->url;
            $screenshot_id = "http://getscreenshots.io/api/ss/" . end(explode('/', $screenshot_decoded));
        }

        return $screenshot_id;
    }

    public function getScreenshotURL($screenshot_id){
        $screenshot_url = "";

        if(!is_null($screenshot_id)){
            while($screenshot_url == ""){
                //creamos un nuevo cURL resource
                $ch = curl_init();
                //seteamos las opciones
                curl_setopt($ch, CURLOPT_URL, $screenshot_id);
                curl_setopt($ch, CURLOPT_HEADER, 0);  // No HTTP headers
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // return the data
                //ejecutamos el post
                $resultset = curl_exec($ch);
                //cerramos la coneccion
                curl_close($ch);
                //obtenemos la URL de la imagen
                $screenshot_url_json = json_decode($resultset);
                //si el JSON es el correcto, sale
                if(count($screenshot_url_json->images) > 0){
                    foreach($screenshot_url_json->images as $thumb){
                        if($thumb->width == 1360 && $thumb->height == 768){
                            $screenshot_url = $thumb->url;
                        }
                    }
                }
                //esperamos un delay antes de reintentar
                sleep(1);
            }
        }

        return $screenshot_url;
    }

    public function guardarPortada($nombre, $screenshot_url){
        //obtenemos el archivo de la URL y lo guardamos en disco
        $data = file_get_contents($screenshot_url);
        $file = fopen($nombre, "w+");
        fputs($file, $data);
        fclose($file);
    }

    public function redimensionarPortada($nombre){
        //leemos el archivo para recortarlo y redimensionarlo
        $crop_image = imagecreatetruecolor(750, 1200);
        $src_image = @imagecreatefromjpeg(Configure::read('dominio') . $nombre);
        imagecopyresampled($crop_image, $src_image, 0, 0, 0, 0, 750, 1200, 1360, 2176);
        imagejpeg($crop_image, $nombre);
        imagedestroy($crop_image);
    }

    public function obtenerPortadaDiarios(){
        $portadas = [];

        $path_imagenes_portada = Configure::read('path_imagen_portadas') . '*';
        $portales = TableRegistry::get('Portales')->find('all')
            ->select([
                'Portales.nombre',
                'Portales.codigo',
                'Portales.url'
                ])
            ->where(['Portales.en_portada' => 1])
            ->order(['Portales.codigo' => 'ASC'])->toArray();

        $portadas_en_carpeta = glob($path_imagenes_portada);
        foreach($portales as $portal){
            if($this->estaEnCarpetaPortada($path_imagenes_portada, $portal->codigo) === true){
                $portadas[] = ['portal' => $portal->nombre, 'url' => $portal->url, 'imagen' => $this->getPathPortada($portal->codigo, $portadas_en_carpeta)];
            }
        }

        return $portadas;
    }

    public function getPathPortada($codigo, $portadas){
        $path_portada = '';

        foreach($portadas as $portada){
            if(strpos($portada, $codigo) !== false){
                $path_portada = $portada;
            }
        }

        return $path_portada;
    }
}