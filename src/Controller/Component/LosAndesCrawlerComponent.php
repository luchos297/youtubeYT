<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use Exception;
/**
 * Description of LosandesCrawlerComponent
 *
 * @author Jesús
 */
class LosandesCrawlerComponent extends BaseCrawlerComponent{

    private $secciones_diario = [
        'política' => 'POLITICA',
        'departamentales' => 'PROVINCIALES',
        'vecinos' => 'PROVINCIALES',
        'internacionales' => 'INTERNACIONALES',
        'policiales' => 'POLICIALES',
        'mas-deportes' => 'DEPORTES',
        'soy tecno' => 'TECNOLOGIA',
        'sociedad' => 'SOCIEDAD',
        'rumbos' => 'SOCIEDAD',
        'sociales' => 'SOCIALES',
        'estilo' => 'SOCIEDAD',
        'mundo' => 'INTERNACIONALES',
        'Economía' => 'ECONOMIA'
        ];

    private $excluye_seccion = [
        'editorial', 
        'escribe el lector',
        'mirador',
        'opinión'
        ];  

    public function runCrawler(){
        set_time_limit(0);
        $this->rssesTable = TableRegistry::get('Rsses');
        $this->articulosTable = TableRegistry::get('Articulos');

        $rss = $this->rssesTable->find()
                ->where(['habilitado', 'Portales.codigo' => $this->codigo ])
                ->contain(['Portales'])
                ->first();

        $guardados = [$this->codigo =>[]];
        if(!is_null($rss)){
            $context = $this->getStreamContext();
            try{
                $content_xml = file_get_contents($rss->url,false,$context);
                if (isset($content_xml)) {   
                    $noticias = @simplexml_load_string($content_xml);
                    if(!$noticias)return $guardados[$this->codigo][] = "No se pudo acceder al rss";
                    foreach ($noticias->channel->item as $noticia) {
                        if($this->existTitle((string)$noticia->title) && !in_array(trim($noticia->title),$guardados[$this->codigo])){
                            try{
                                @$this->setHtmlDomFromString($noticia->link,$context);
                                if($this->html){
                                    $this->clearNode('script');
                                    $articulo = $this->articulosTable->association('Imagenes')->newEntity();
                                    $articulo->publicado = $this->verificarIntegridadFechaNoticia($this->getFechaPublicado());
                                    $articulo->titulo = trim((string)$noticia->title);
                                    $articulo->descripcion = trim((string)$noticia->description);
                                    $articulo->texto = $this->getContenido();
                                    $articulo->creado = date("Y-m-d H:i:s");
                                    $articulo->categoria_id = $this->getCategorias();
                                    $articulo->portal_id = $rss->portal_id;
                                    $articulo->habilitado = true;
                                    $articulo->url_rss = trim($noticia->link);
                                    $articulo->visitas = 0;

                                    if ($this->articulosTable->save($articulo)) {
                                        $imagenes = $this->getImagenes();
                                        foreach($imagenes as $imagen){
                                            $imagen_path = $imagen['path'];
                                            $imagen_descripcion = $imagen['descripcion'];
                                            $imagen_name = explode("/", $imagen['path']);
                                            end($imagen_name);
                                            $imagen = $this->saveImagen($imagen_path, $imagen_descripcion, current($imagen_name),true);
                                            if($imagen != null){
                                                $this->getConnection()->insert('articulo_imagen', ['articulo_id' => $articulo->id,'imagen_id' => $imagen->id]);
                                            }
                                        }
                                        $guardados[$this->codigo][] = trim($articulo->titulo);
                                    }
                                }
                                //exit;
                            }
                            catch(Exception $e){}
                        }
                    }
                }
            }
            catch (Exception $e){
                $guardados[$this->codigo][]= "No se pudo acceder al rss";
            }            
        }
        return $guardados;
    }

    public function getContenido(){
        $texto = "";

        $this->clearNode('.relacionadas');
        $data1 = $this->html->find('.cuerponota', 0)->find('.row-fluid');
        if(!is_null($data1)){
            $data1 = end($data1)->find('.span10', 0);
            foreach($data1->children as $data){
                //quotes
                if($data->tag == "blockquote"){
                    $texto_b = trim($data->find('p', 0));
                    if($texto_b != "" && $texto_b != " "){
                        $texto .= "<blockquote class='style-2'><p><h3>" . strip_tags(preg_replace('#<iframe id="twitter-widget(.*?)</iframe>#', '', $data->plaintext),'<b><strong>') . "</h3></p></blockquote>#";
                    }
                }
                //imagenes
                elseif($data->tag == "p" && !is_null($data->find('img', 0))){
                    $image_src = $data->find('img', 0);
                    $image_src_full =  "http://www.losandes.com.ar" . $image_src->attr['src'];
                    $texto .= "<img src='" . $image_src_full . "' style='display: block; margin: 0 auto;' height='70%'' width='70%'>#";
                }
                //videos de youtube
                elseif($data->tag == "p" && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "youtube") !== false){
                    $video_src = $data->find('iframe', 0);
                    $texto .= "<iframe width='576' height='576' src='" . $video_src->attr['src'] . "' frameborder='0' style='display: block; margin: 0 auto;' class=' pinged loaded in-view'></iframe>#";
                }
                //storify
                elseif($data->tag == "div" && !is_null($data->find('div', 0))){
                    if(strpos($data->find('div', 0)->attr['class'], "storify") !== false){
                        $storify_src = $data->find('iframe', 0);
                        $texto .= "<iframe src='" . $storify_src->attr['src'] . "' width='75%' height='750' frameborder='no' allowtransparency='true' style='display: block; margin: 0 auto;'></iframe>#";
                    }
                }
                //twitter con el texto y foto
                elseif($data->tag == "div" && !is_null($twitter = $data->find('blockquote', 0)) && strpos($twitter->find('a', count($data->find('a')) - 2)->attr['href'], "t.co") !== false && strpos($twitter->find('a', count($data->find('a')) - 1)->attr['href'], "twitter") !== false){
                    $twitter_src_foto = $data->find('blockquote', 0)->find('a');
                    $texto .= "<blockquote align='center' class='twitter-tweet' data-lang='en'><p lang='es' dir='ltr'><a href='" . $twitter_src_foto[0]->attr['href'] . "'></a></p><a href='" . end($twitter_src_foto)->attr['href'] . "'></a></blockquote><script async src='//platform.twitter.com/widgets.js' charset='utf-8'></script>#";
                }
                //twitter con el texto
                elseif($data->tag == "div" && !is_null($twitter = $data->find('blockquote', 0)) && strpos($twitter->find('a', 0)->attr['href'], "twitter") !== false){
                    $twitter_src = $data->find('blockquote', 0)->find('a', 0);
                    $texto .= "<blockquote align='center' class='twitter-tweet' data-lang='es'><p lang='es' dir='ltr'><a href='" . $twitter_src->attr['href'] . "'></a></p></blockquote><script async src='//platform.twitter.com/widgets.js' charset='utf-8'></script>#";
                }
                //audio de radiocut
                elseif($data->tag == "div" && !is_null($data->find('iframe', 0)) && strpos($data->find('iframe', 0)->attr['src'], "radiocut") !== false){
                    $audio_src = $data->find('iframe', 0);
                    $texto .= "<iframe width='100%' height='65px' allowtransparency='true' src='" . $audio_src->attr['src'] . "' frameborder='no' scrolling='no'></iframe>#";
                }
                //texto
                elseif($data->tag == "p"){
                    $texto_p = strip_tags($data->innertext, '<b><strong>'); 
                    $texto_p = preg_replace('/\s\s+|&nbsp;/', '', $texto_p);
                    if($texto_p != "" && $texto_p != " "){
                        $texto .= '<p>'.strip_tags(preg_replace('#<iframe id="twitter-widget(.*?)</iframe>#', '', $data->innertext), '<b><strong>').'</p>#';
                    }
                }
                else{
                    $texto .= "";
                }
            }
        }

        return $texto;
    }

    public function getImagenes(){
        $imagenes = [];

        $data2 =  $this->html->find('.carousel-inner', 0);
        if(!is_null($data2)){
            foreach($data2->children as $imagen){
                if(!is_null($imagen->find('img', 0)) && strpos($imagen->find('img', 0)->attr['src'], "image") !== false){
                    $image_src = $imagen->find('img', 0);
                    if(!is_null($imagen->find('.ep', 0))){
                        $imagenes[] = [
                            'path' => "http://www.losandes.com.ar" . html_entity_decode($image_src->attr['src']),
                            'descripcion' => trim($imagen->find('.ep', 0)->plaintext)
                            ];
                    }
                    else{
                        $imagenes[] = [
                            'path' => "http://www.losandes.com.ar" . html_entity_decode($image_src->attr['src']),
                            'descripcion' => ''
                            ];
                    }
                }
            }
        }

        return $imagenes;
    }

    /*public function getFechaPublicadoHtml(){
        $data3 = $this->html->find('header ul.fecha', 0);
        $date_str = "";
        foreach($data3->find('li') as $li)
        {
            if($li->class == 'notafecha') {
                //Jueves, 22 de octubre de 2015
                //Wed, 21 Oct 2015 13:09:20 GMT
                $date_arr = explode(" ",str_replace(",","",trim($li->plaintext)));
                $date_arr[0] = array_key_exists(strtolower($date_arr[0]),
                        $this->getDaysMap())?$this->getDaysMap()[strtolower($date_arr[0])]:$date_arr[0];
                $date_arr[3] = array_key_exists(strtolower($date_arr[3]),
                        $this->getMonthsMap())?$this->getMonthsMap()[strtolower($date_arr[3])]:$date_arr[3];
                $date_str = $date_arr[0].", ".$date_arr[1]." ".$date_arr[3]." ".$date_arr[5];
            }
            if($li->class == 'edicionimpresa') {
                $date_pub = strtotime($date_str." 00:00:00");
                return date("Y-m-d H:i:s", $date_pub);
            }
            else if($li->class == 'actualizado1'){
                $time_arr = explode(" ",trim($li->plaintext));
                $time_str = array_pop($time_arr);

                $date_pub = strtotime($date_str." ".$time_str.":00");
                return date("Y-m-d H:i:s", $date_pub);
            }
            else if(strlen($date_str) > 0){
                $date_pub = strtotime($date_str." 00:00:00");
                return date("Y-m-d H:i:s", $date_pub);
            }
        }
    }*/

    public function getFechaPublicado(){
        $fecha = "";

        $data3 = $this->html->find('.notafecha', 0);
        if(!is_null($data3)){
            $fecha_noticia = explode(",", $data3->plaintext);
            $fecha_noticia = trim(end($fecha_noticia));
            $fecha_dia = (strlen(trim(substr($fecha_noticia, 0, 2))) < 2) ? '0' . trim(substr($fecha_noticia, 0, 2)) : trim(substr($fecha_noticia, 0, 2));
            $fecha_mes = $this->getMes($fecha_noticia);
            $fecha_año = trim(substr($fecha_noticia, -4));
            if(!is_null($this->html->find('.actualizado1', 0))){
                $hora = trim(substr($this->html->find('.actualizado1', 0)->plaintext, -5));
                $fecha = $fecha_año . '-' . $fecha_mes . '-' . $fecha_dia . ' ' . $hora;   
            }
            elseif(!is_null($this->html->find('.actualizado', 0))){
                $hora2 = trim($this->html->find('.actualizado', 0)->plaintext);
                $hora2_final = trim(substr($hora2, -4));
                $fecha = $fecha_año . '-' . $fecha_mes . '-' . $fecha_dia . ' ' . date("H:i:m", strtotime("-" . preg_replace("/'/", '', $hora2_final) . "minute"));
            }
            else{
                $fecha = $fecha_año . '-' . $fecha_mes . '-' . $fecha_dia . ' ' . date("H:i:m");
            }
        }

        return $fecha;
    }

    public function getMes($month){
        switch ($month) {
            case strpos($month, "enero") !== false:
                $mes = "01";
                break;
            case strpos($month, "febrero") !== false:
                $mes = "02";
                break;
            case strpos($month, "marzo") !== false:
                $mes = "03";
                break;
            case strpos($month, "abril") !== false:
                $mes = "04";
                break;
            case strpos($month, "mayo") !== false:
                $mes = "05";
                break;
            case strpos($month, "junio") !== false:
                $mes = "06";
                break;
            case strpos($month, "julio") !== false:
                $mes = "07";
                break;
            case strpos($month, "agosto") !== false:
                $mes = "08";
                break;
            case strpos($month, "septiembre") !== false:
                $mes = "09";
                break;
            case strpos($month, "octubre") !== false:
                $mes = "10";
                break;
            case strpos($month, "noviembre") !== false:
                $mes = "11";
                break;
            case strpos($month, "diciembre") !== false:
                $mes = "12";
                break;
        }
        return $mes;
    }

    public function getCategorias(){
        $codigo = 'EXTRA';

        //es una estructura de noticia normal
        if(count($this->html->find('div.seccion')) > 0){
            $seccion = trim(strtolower($this->html->find('div.seccion')[0]->plaintext));

            if(array_key_exists($seccion, $this->secciones_diario)){
                $codigo = $this->secciones_diario[strtolower(trim($seccion))];
            }
            else if(in_array($seccion, $this->excluye_seccion)){
                throw new Exception("Categoria innecesaria.");
            }
        }
        //seccion mas-deportes
        else if(count($this->html->find('.span3')) > 0 && count($this->html->find('.span3')[0]->find('a')) > 0){
            $seccion_link = $this->html->find('.span3')[0]->find('a')[0]->href;
            $seccion_link_arr = explode('/',$seccion_link);
            if(in_array('mas-deportes',$seccion_link_arr)){
                $codigo = $this->secciones_diario['mas-deportes'];
            }
            else if(in_array(strtolower(trim($seccion)), $this->excluye_seccion)){
                throw new Exception("Categoria innecesaria.");
            }
        }
        //seccion estilo
        else if(count($this->html->find('.span8')) > 0 && count($this->html->find('.span8')[0]->find('a')) > 0){
            $seccion_link = $this->html->find('.span8')[0]->find('a')[0]->href;
            $seccion_link_arr = explode('/',$seccion_link);
            if(in_array('estilo',$seccion_link_arr)){
                $codigo = $this->secciones_diario['estilo'];
            }
            else if(in_array(strtolower(trim($seccion)), $this->excluye_seccion)){
                throw new Exception("Categoria innecesaria.");
            }
        }

        $categoria_id = TableRegistry::get('Categorias')
                        ->find()
                        ->where(['Categorias.codigo' => $codigo])
                        ->first()
                        ->id;

        return $categoria_id;
    }
}
