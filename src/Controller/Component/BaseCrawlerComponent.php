<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Filesystem\Folder;
use simple_html_dom;

class BaseCrawlerComponent extends Component
{    
    public $components = ['Uploader', 'Imagen','Connection'];
    
    protected $_defaultConfig = [
        'root' => WWW_ROOT,
        'suffix' => '_file',
        'fields' => []
    ];
    
    protected $codigo = NULL;
    protected $categoria = NULL;
    protected $rss = NULL;
    protected $html = NULL;
    protected $uploadPath = NULL;
    protected $streamContext = NULL;
    protected $deflateContext = NULL;


    // Execute any other additional setup for your component.
    public function initialize(array $config)
    {
        $this->setStreamContext();
        //$this->setDeflateContext();
    }
    
    public function setCodigo($codigo){
        $this->codigo = $codigo;
    }
    
    public function setCategoria($codigo){
        $this->categoria = $codigo;
    }
    
    public function setRss($rss){
        $this->rss = $rss;
    }
    
    public function setSimpleHtmlDomFromUrl($url){
        $this->html = new simple_html_dom();
        $this->html->load_file($url);
    }
    
    public function setHtmlDomFromUrl($url){
        $this->html = new simple_html_dom();
        $this->html = @file_get_html($url);
    }
    
    public function setHtmlDomFromString($url,$context){
        $this->html = new simple_html_dom();
        $this->html->load(@file_get_contents($url,false,$context),true);
    }
    
    public function setHtmlDomFromContent($content){
        $this->html = new simple_html_dom();
        $this->html->load($content);
    }
    
    public function runCrawler(){}
    
    public function getTitulo(){}
    
    public function getDescripcion(){}
    
    public function getContenido(){}
    
    public function getImagenes(){}    
    
    public function getFechaPublicadoHtml(){}
    
    public function getPalabrasClaves($opcion = null){
        switch($opcion){
            case null:
                $keys = $this->html->find("meta[name=keywords]", 0);
                break;
            case 1:
                $keys = $this->html->find("meta[name=KEYWORDS]", 0);
                break;
            case 2:
                $keys = $this->html->find("meta[property=article:tag]");
                break;
        }
        return $keys;
    }
    
    public function getFechaPublicadoByRss(){}
    
    public function getCategoria($url = null){}
    
    public function setStreamContext(){
        $opts = [
            'http'=>[
                'header' => "User-Agent:MyAgent/1.0\r\n"
                ]
            ];
        $this->streamContext = stream_context_create($opts);
    }
    
    public function setDeflateContext(){
        $opts = [
            'http'=>[
                'method'=>"GET",
                'header'=>"Accept-Encoding: gzip;q=0,deflate,sdch\r\n"
                ]
            ];
        $this->deflateContext = stream_context_create($opts);
    }
    
    public function getStreamContext(){        
        return $this->streamContext;
    }
    
    public function getDeflateContext(){        
        return $this->deflateContext;
    }
    
    public function getConnection(){
        return $this->Connection->getConnection();
    }
    
    public function getDaysMap(){
        $days = ['lunes'=>'Mon', 'martes'=>'Tue','miércoles'=>'Wed',
            'jueves'=>'Thu','viernes'=>'Fri','sábado'=>'Sat','domingo'=>'Sun'];
        return $days;
    }
    
    public function existTitle($title){
        return TableRegistry::get('Articulos')->find('all')
                ->where(['Articulos.titulo' => trim($title)])
                ->count() == 0;
    }
    
    public function getMonthsMap(){
        $moths = ['enero'=>'Jan','febrero'=>'Feb','marzo'=>'Mar','abril'=>'Apr',
            'mayo'=>'May','junio'=>'Jun','julio'=>'Jul','agosto'=>'Aug',
            'septiembre'=>'Sep','setiembre'=>'Sep','octubre'=>'Oct',
            'noviembre'=>'Nov','diciembre'=>'Dec'];
        return $moths;
    }
    
    public function getStateHeaderXML($url){
        $url_headers = @get_headers($url);
        if($url_headers[0] == 'HTTP/1.1 200 OK' or 
                $url_headers[0] == 'HTTP/1.0 200 OK') {
            $response = ['ok'=>true,'state'=>$url_headers[0]];            
        } else {
            // Error
            $response = ['ok'=>false,'state'=>$url_headers[0]];
        }
        return $response;
    }    
    
    public function clearNode($selector){
        
        foreach ($this->html->find($selector) as $node)
        {
            $node->outertext = '';
        }

        $this->html->load($this->html->save());         
    }
    
    public function clearNodeDom($selector, $dom){
        
        foreach ($dom->find($selector) as $node)
        {
            $node->outertext = '';
        }

        return $dom->load($dom->save()); 
    }
    
    function getProxy() {
        $data = json_decode(file_get_contents('http://gimmeproxy.com/api/get/8bb99df808d75d71ee1bdd9e5d/?timeout=0'), 1);
        if(isset($data['error'])) { // there are no proxies left for this user-id and timeout
            echo $data['error']."\n";
        } 
        return isset($data['error']) ? false : $data['curl'];
    }

    function getWithProxy($url) {
        $curlOptions = array(
            CURLOPT_CONNECTTIMEOUT => 25,
            CURLOPT_TIMEOUT => 25,
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 9,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HEADER => 0,
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36",
            CURLINFO_HEADER_OUT  => true,
        );
        $curl = curl_init();
        curl_setopt_array($curl, $curlOptions);
        if($proxy = $this->getProxy()) {
            //echo 'set proxy '.$proxy."\n";
            curl_setopt($curl, CURLOPT_PROXY, $proxy);
        }
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }
    
    function isHtml($string)
    {
        return $string != strip_tags($string) ? true:false;
    }
    
    public function saveImagen($imagen_path, $image_caption, $imagen_name, $use_context=false) {
        $imagen = TableRegistry::get('Articulos')->Imagenes->newEntity();
        $this->uploadPath = 'files/imagenes/filename';
        $nombre_extension = explode(".", $imagen_name);
        $extension = strtolower(array_pop($nombre_extension));        
        $name = implode('_',$nombre_extension);       
        
        $imagen->dirftp = 'src="' . $imagen_path . '"';
        $error = false;
        
        try{
            $lastPathName = $this->Imagen->getLastUploadPath();
            $this->uploadPath .= DS . $lastPathName;            
            
            if (!file_exists($this->_defaultConfig['root'] . $this->uploadPath)) {
                $p = $this->_defaultConfig['root'].$this->uploadPath;
                if(!new Folder($p, true, 0755)){
                    return null;
                }
            }           
            
            if($use_context){
                @$file_content = file_get_contents($imagen_path);
                if($file_content != false){
                    file_put_contents(TMP . $imagen_name, $file_content);
                }
                else{
                    return null;
                }
            }
            else{
                $req = 0;
                while(!@copy($imagen_path, TMP . $imagen_name)){
                    if($req > 7){
                        return null;
                    }
                    $req++;
                }
                
            }
            
            $mime_type = 'image/'.$extension;
            
            if($extension == 'jpg'){
                $mime_type = 'image/jpeg';
            }
    
            $size = filesize(TMP . $imagen_name);
            $array = array(
                'filename' => array(
                    'name' => $name,
                    //'type' => 'image/jpeg',
                    'type' => $mime_type,
                    'tmp_name' => TMP . $imagen_name,
                    'error' => 0,
                    'size' => $size),
                'creado' => date('Y-m-d')
            );
            $newName = $lastPathName.'.'.$extension;
            $error = $this->Uploader->handleFileUpload($array['filename'], $newName, $this->validateFile, $this->uploadPath);

            $imagen_id = 0;
            if(!$error){
                $imagen->filename = $newName;
                $imagen->descripcion = $image_caption;
                $imagen->creado = date("Y-m-d H:i:s");
                $imagen->file_url = $lastPathName;
                if(TableRegistry::get('Imagenes')->save($imagen)){
                    $imagen_id = $imagen->id;
                    //$data = ['_ids' => [$imagen_id]];
                    //$data = [['id' => $imagen_id]];
                    return $imagen;
                }
            }
            else{
                return null;
            }
        }
        catch (Exception $e){
            return null;
        }
    }

    public function verificarIntegridadFechaNoticia($fecha_noticia){
        $fecha_actual = new \DateTime();
        $fecha_noticia = new \DateTime($fecha_noticia);

        if ($fecha_actual < $fecha_noticia) {
            //año, mes y dia mayor al actual   2018-10-29 15:56:43
            if($fecha_noticia->format('Y') > $fecha_actual->format('Y') && 
                   $fecha_noticia->format('m') > $fecha_actual->format('m') &&
                   $fecha_noticia->format('d') > $fecha_actual->format('d') ){
                $fecha_noticia->setDate($fecha_actual->format('Y'), $fecha_actual->format('m'), $fecha_actual->format('d'));
            }
            //año y mes mayor al actual   2018-10-12 15:56:43
            elseif($fecha_noticia->format('Y') > $fecha_actual->format('Y') && 
                   $fecha_noticia->format('m') > $fecha_actual->format('m')){
                $fecha_noticia->setDate($fecha_actual->format('Y'), $fecha_actual->format('m'), $fecha_noticia->format('d'));
            }
            //año y dia mayor al actual   2018-07-29 15:56:43
            elseif($fecha_noticia->format('Y') > $fecha_actual->format('Y') && 
                   $fecha_noticia->format('d') > $fecha_actual->format('d')){
                $fecha_noticia->setDate($fecha_actual->format('Y'), $fecha_noticia->format('m'), $fecha_actual->format('d'));
            }
            //año mayor al actual   2018-07-12 15:56:43
            if($fecha_noticia->format('Y') > $fecha_actual->format('Y')){
                $fecha_noticia->setDate($fecha_actual->format('Y'), $fecha_noticia->format('m'), $fecha_noticia->format('d'));
            }
            //mes y dia mayor al actual   2016-09-29 15:56:43
            elseif($fecha_noticia->format('m') > $fecha_actual->format('m') &&
                   $fecha_noticia->format('d') > $fecha_actual->format('d') ){
                $fecha_noticia->setDate($fecha_noticia->format('Y'), $fecha_actual->format('m'), $fecha_actual->format('d'));
            }
            //mes mayor al actual   2016-09-12 15:56:43
            elseif($fecha_noticia->format('Y') == $fecha_actual->format('Y') && 
                   $fecha_noticia->format('m') > $fecha_actual->format('m')){
                $fecha_noticia->setDate($fecha_noticia->format('Y'), $fecha_actual->format('m'), $fecha_noticia->format('d'));
            }
            //dia mayor al actual   2016-07-29 15:56:43
            elseif($fecha_noticia->format('Y') == $fecha_actual->format('Y') && 
                   $fecha_noticia->format('m') == $fecha_actual->format('m') &&
                   $fecha_noticia->format('d') > $fecha_actual->format('d') ){
                $fecha_noticia->setDate($fecha_noticia->format('Y'), $fecha_noticia->format('m'), $fecha_actual->format('d'));
            }
            else{}
        }
        $fecha_noticia_final = $fecha_noticia->setTime($fecha_noticia->format('H'), $fecha_noticia->format('i'), $fecha_noticia->format('s'));
        $fecha_noticia_final = $fecha_noticia->format('Y-m-d H:i:s');

        return $fecha_noticia_final;
    }
}
?>
