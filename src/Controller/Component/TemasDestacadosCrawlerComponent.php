<?php
namespace App\Controller\Component;

use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;

/**
 * Description of MendozaPostCrawlerComponent
 *
 * @author Luciano
 */
class TemasDestacadosCrawlerComponent extends BaseCrawlerComponent{ 

    public function baseCrawler(){

        //seteamos los portales a buscar
        $portales = [
            'AMBITO',
            'CIUDADANO',
            'CLARIN',
            'DIARIOUNO',
            'ELSOL',
            'INFOBAE',
            'LANACION',
            'LOSANDES',
            'MDZ',
            'MENDOZAPOST',
            'MINUTOUNO',
            'PAGINA12',
            'SITIOANDINO',
            'TELAM'
        ];

        set_time_limit(0);
        $this->rssesTable = TableRegistry::get('Rsses');
        $this->articulosTable = TableRegistry::get('Articulos');        

        $rsses = [];
        //buscamos cada uno en la base de datos
        foreach($portales as $portal){
            $rss = $this->rssesTable->find()
                    ->where(['habilitado', 'Portales.codigo' => $portal])
                    ->contain(['Portales'])
                    ->first();
            array_push($rsses, $rss);
        }

        $guardados = [];
        if(!is_null($rsses)){
            foreach($rsses as $key => $rss){

                switch ($portales[$key]) {
                    case "AMBITO":
                        $state = $this->getStateHeaderXml($rss->portal->url);
                        if(!is_null($state['ok']) && strpos($state['state'], "200 OK") !== false){
                            try{
                                @$this->setHtmlDomFromString($rss->portal->url, $this->getStreamContext());
                                $links = $this->getLinksAmbitoDestacados();
                                if($this->html && count($links) > 0){
                                    foreach($links as $link){
                                        try{
                                            @$this->setHtmlDomFromString($link['link'], $this->getStreamContext());  
                                            if($this->html){                                     
                                                $guardados += $this->getNoticiasAmbitoDestacados();
                                            }
                                            else{
                                                array_push($guardados, "Error al obtener los links destacados de 'Ámbito'");
                                            }
                                        }
                                        catch(Exception $e){}
                                    }
                                }
                                else{
                                    array_push($guardados, "Error al obtener los links destacados de 'Ámbito'");
                                }
                            }
                            catch(Exception $e){}
                            break;
                        }
                        else{
                            array_push($guardados, "Error al acceder al sitio de 'Ámbito': " . $state['state']);
                        }
                    case "CIUDADANO":
                        $state = $this->getStateHeaderXml($rss->portal->url);
                        if(!is_null($state['ok']) && strpos($state['state'], "200 OK") !== false){
                            try{
                                @$this->setHtmlDomFromString($rss->portal->url, $this->getStreamContext());
                                if($this->html){                                     
                                    $guardados += $this->getNoticiasElCiudadanoDestacados($rss->url);
                                }
                                else{
                                    array_push($guardados, "Error al obtener los links destacados de 'El Ciudadano'");
                                }
                            }
                            catch(Exception $e){}
                            break;
                        }
                        else{
                            array_push($guardados, "Error al acceder al sitio de 'El Ciudadano': " . $state['state']);
                        }
                    case "CLARIN":
                        $state = $this->getStateHeaderXml($rss->portal->url);
                        if(!is_null($state['ok']) && strpos($state['state'], "200 OK") !== false){
                            try{
                                $noticias = @simplexml_load_file($rss->url);
                                if(!is_null($noticias)){
                                    $guardados += $this->getNoticiasClarinDestacados($noticias);
                                }
                                else{
                                    array_push($guardados, "Error al obtener los links destacados de 'Clarin'");
                                }
                            }
                            catch(Exception $e){}
                            break;
                        }
                        else{
                            array_push($guardados, "Error al acceder al sitio de 'Clarin': " . $state['state']);
                        }   
                    case "DIARIOUNO":
                        $state = $this->getStateHeaderXml($rss->portal->url);
                        if(!is_null($state['ok']) && strpos($state['state'], "200 OK") !== false){
                            try{
                                @$this->setHtmlDomFromString($rss->portal->url, $this->getStreamContext());  
                                if($this->html){                                     
                                    $guardados += $this->getNoticiasDiarioUnoDestacados();
                                }
                                else{
                                    array_push($guardados, "Error al obtener los links destacados de 'Diario UNO'");
                                }
                            }
                            catch(Exception $e){}
                            break;
                        }
                        else{
                            array_push($guardados, "Error al acceder al sitio de 'Diario UNO': " . $state['state']);
                        }
                    case "ELSOL":
                        $state = $this->getStateHeaderXml($rss->portal->url);
                        if(!is_null($state['ok']) && strpos($state['state'], "200 OK") !== false){
                            try{
                                @$this->setHtmlDomFromString($rss->portal->url, $this->getStreamContext());  
                                if($this->html){                                     
                                    $guardados += $this->getNoticiasELSolDestacados();
                                }
                                else{
                                    array_push($guardados, "Error al obtener los links destacados de 'El Sol'");
                                }
                            }
                            catch(Exception $e){}
                            break;
                        }
                        else{
                            array_push($guardados, "Error al acceder al sitio de 'El Sol': " . $state['state']);
                        }
                    case "INFOBAE":
                        $state = $this->getStateHeaderXml($rss->portal->url);
                        if(!is_null($state['ok']) && strpos($state['state'], "200 OK") !== false){
                            try{
                                $noticias = @simplexml_load_file($rss->url);
                                if(!is_null($noticias)){
                                    $guardados += $this->getNoticiasInfobaeDestacados($noticias);
                                }
                                else{
                                    array_push($guardados, "Error al obtener los links destacados de 'Infobae'");
                                }
                            }
                            catch(Exception $e){}
                            break;
                        }
                        else{
                            array_push($guardados, "Error al acceder al sitio de 'Infobae': " . $state['state']);
                        }
                    case "LANACION":
                        $state = $this->getStateHeaderXml($rss->portal->url);
                        if(!is_null($state['ok']) && strpos($state['state'], "200 OK") !== false){
                            try{
                                @$this->setHtmlDomFromString($rss->portal->url, $this->getStreamContext());
                                $links = $this->getLinksLaNacionDestacados();
                                if($this->html && count($links) > 0){
                                    foreach($links as $link){
                                        try{
                                            @$this->setHtmlDomFromString($link['link'], $this->getStreamContext());  
                                            if($this->html){
                                                $guardados += $this->getNoticiasLaNacionDestacados();
                                            }
                                            else{
                                                array_push($guardados, "Error al obtener los links destacados de 'La Nacion'");
                                            }
                                        }
                                        catch(Exception $e){}
                                    }
                                }
                                else{
                                    array_push($guardados, "Error al obtener los links destacados de 'La Nacion'");
                                }
                            }
                            catch(Exception $e){}
                            break;
                        }
                        else{
                            array_push($guardados, "Error al acceder al sitio de 'La Nacion': " . $state['state']);
                        }
                    case "LOSANDES":
                        $context = $this->getStreamContext();
                        $content_xml = file_get_contents($rss->url, false, $context);
                        if(isset($content_xml)){
                            try{
                                $noticias = @simplexml_load_string($content_xml);
                                if(!is_null($noticias)){
                                    $guardados += $this->getNoticiasLosAndesDestacados($noticias);
                                }
                                else{
                                    array_push($guardados, "Error al obtener los links destacados de 'Los Andes'");
                                }
                            }
                            catch(Exception $e){}
                            break;
                        }
                        else{
                            array_push($guardados, "Error al acceder al sitio de 'Los Andes': " . $state['state']);
                        }
                    case "MDZ":
                        $state = $this->getStateHeaderXml($rss->portal->url);
                        if(!is_null($state['ok']) && strpos($state['state'], "200 OK") !== false){
                            try{    
                                @$this->setHtmlDomFromString($rss->portal->url, $this->getStreamContext());
                                $links = $this->getLinksMdzDestacados();
                                if($this->html && count($links) > 0){
                                    foreach($links as $link){
                                        try{
                                            @$this->setHtmlDomFromString($link['link'], $this->getStreamContext());  
                                            if($this->html){
                                                $guardados += $this->getNoticiasMdzDestacados();
                                            }
                                            else{
                                                array_push($guardados, "Error al obtener los links destacados de 'MDZ'");
                                            }
                                        }
                                        catch(Exception $e){}
                                    }
                                }
                                else{
                                    array_push($guardados, "Error al obtener los links destacados de 'MDZ'");
                                }
                            }
                            catch(Exception $e){}
                            break;
                        }
                        else{
                            array_push($guardados, "Error al acceder al sitio de 'MDZ': " . $state['state']);
                        }
                    case "MENDOZAPOST":
                        $state = $this->getStateHeaderXml($rss->portal->url);
                        if(!is_null($state['ok']) && strpos($state['state'], "200 OK") !== false){
                            try{
                                @$this->setHtmlDomFromString($rss->portal->url, $this->getStreamContext());  
                                if($this->html){
                                    $guardados += $this->getNoticiasMendozaPostDestacados();
                                }
                                else{
                                    array_push($guardados, "Error al obtener los links destacados de 'Mendoza Post'");
                                }
                            }
                            catch(Exception $e){}
                            break;
                        }
                        else{
                            array_push($guardados, "Error al acceder al sitio de 'Mendoza Post': " . $state['state']);
                        }
                    case "MINUTOUNO":
                        $state = $this->getStateHeaderXml($rss->portal->url);
                        if(!is_null($state['ok']) && strpos($state['state'], "200 OK") !== false){
                            try{    
                                @$this->setHtmlDomFromString($rss->portal->url, $this->getStreamContext());
                                $links = $this->getLinksMinutoUnoDestacados();
                                if($this->html && count($links) > 0){
                                    foreach($links as $link){
                                        try{
                                            @$this->setHtmlDomFromString($link['link'], $this->getStreamContext());  
                                            if($this->html){
                                                $guardados += $this->getNoticiasMinutoUnoDestacados();
                                            }
                                            else{
                                                array_push($guardados, "Error al obtener los links destacados de 'Minuto Uno'");
                                            }
                                        }
                                        catch(Exception $e){}
                                    }
                                }
                                else{
                                    array_push($guardados, "Error al obtener los links destacados de 'Minuto Uno'");
                                }
                            }
                            catch(Exception $e){}
                            break;
                        }
                        else{
                            array_push($guardados, "Error al acceder al sitio de 'Minuto Uno': " . $state['state']);
                        }
                    case "PAGINA12":
                        $state = $this->getStateHeaderXml($rss->portal->url);
                        if(!is_null($state['ok']) && strpos($state['state'], "200 OK") !== false){
                            try{
                                $noticias = @simplexml_load_file($rss->url);
                                if(!is_null($noticias)){
                                    $guardados += $this->getNoticiasPagina12Destacados($noticias);
                                }
                                else{
                                    array_push($guardados, "Error al obtener los links destacados de 'Pagina 12'");
                                }
                            }
                            catch(Exception $e){}
                            break;
                        }
                        else{
                            array_push($guardados, "Error al acceder al sitio de 'Pagina 12': " . $state['state']);
                        }
                    case "SITIOANDINO":
                        $state = $this->getStateHeaderXml($rss->portal->url);
                        if(!is_null($state['ok']) && strpos($state['state'], "200 OK") !== false){
                            try{
                                @$this->setHtmlDomFromString($rss->portal->url, $this->getStreamContext());  
                                if($this->html){
                                    $guardados += $this->getNoticiasSitioAndinoDestacados();
                                }
                                else{
                                    array_push($guardados, "Error al obtener los links destacados de 'Sitio Andino'");
                                }
                            }
                            catch(Exception $e){}
                            break;
                        }
                        else{
                            array_push($guardados, "Error al acceder al sitio de 'Sitio Andino': " . $state['state']);
                        }
                    case "TELAM":
                        $state = $this->getStateHeaderXml($rss->portal->url);
                        if(!is_null($state['ok']) && strpos($state['state'], "200 OK") !== false){
                            try{    
                                @$this->setHtmlDomFromString($rss->portal->url, $this->getStreamContext());
                                $links = $this->getLinksTelamDestacados();
                                if($this->html && count($links) > 0){
                                    foreach($links as $link){
                                        try{
                                            @$this->setHtmlDomFromString($link['link'], $this->getStreamContext());  
                                            if($this->html){
                                                $guardados += $this->getNoticiasTelamDestacados();
                                            }
                                            else{
                                                array_push($guardados, "Error al obtener los links destacados de 'Telam'");
                                            }
                                        }
                                        catch(Exception $e){}
                                    }
                                }
                                else{
                                    array_push($guardados, "Error al obtener los links destacados de 'Telam'");
                                }
                            }
                            catch(Exception $e){}
                            break;
                        }
                        else{
                            array_push($guardados, "Error al acceder al sitio de 'Telam': " . $state['state']);
                        }
                }
            }
        }

        return $guardados;
    }

    //Lista de metodos `para recuperar los links de las secciones destacadas
    public function getLinksAmbitoDestacados(){
        $links = [];

        try{
            $links_items = $this->html->find('.tags_portada', 0);            
            if(!is_null($links_items)){                
                foreach($links_items->children as $item){
                    if($item->tag == 'a' && !is_null($item->attr['href'])){
                        $tag = substr($item->attr['href'], 0, strpos($item->attr['href'], '=') + 1);
                        $url_body = substr($item->attr['href'], strpos($item->attr['href'], '=') + 1, strlen($item->attr['href']) - 1);
                        $url = $tag . rawurlencode($url_body);                        
                        $links[] = [
                            'link' => "http://www.ambito.com" . $url
                            ];
                    }
                }
            }
        }
        catch (Exception $e) {}

        return $links;
    }

    public function getLinksLaNacionDestacados(){
        $links = [];

        try{
            $links_items = $this->html->find('.temas-hoy', 0);
            if(!is_null($links_items)){
                $items = $links_items->find('a');
                foreach($items as $item){
                    if((!is_null($item->attr['href']) && strpos($item->attr['href'], "video") === false) && (!is_null($item->attr['href']) && strpos($item->attr['href'], "pm") === false)){
                        $links[] = [
                            'link' => "http://www.lanacion.com.ar" . $item->attr['href']
                            ];
                    }
                }
            }
        }
        catch (Exception $e) {}

        return $links;
    }

    public function getLinksMdzDestacados(){
        $links = [];

        try{
            $links_items = $this->html->find('#hottopics', 0)->find('a');
            if(!is_null($links_items)){                
                foreach($links_items as $item){
                    if(!is_null($item->attr['href'])){
                        $links[] = [
                            'link' => "http://www.mdzol.com" . $item->attr['href']
                            ];
                    }
                }
            }
        }
        catch (Exception $e) {}

        return $links;        
    }

    public function getLinksMinutoUnoDestacados(){
        $links = [];
        
        try{
            $links_items = $this->html->find('.col-sm-9', 0);
            if(!is_null($links_items)){                
                foreach($links_items->children as $item){
                    if(!is_null($item->attr['href'])){
                        $links[] = [
                            'link' => $item->attr['href']
                            ];
                    }
                }
            }
        }
        catch (Exception $e) {}

        return $links;        
    }

    public function getLinksTelamDestacados(){
        $links = [];
        
        try{
            $links_items = $this->html->find('.temas', 0);
            if(!is_null($links_items)){                
                foreach($links_items->children as $item){
                    if(!is_null($item->attr['href'])){
                        $links[] = [
                            'link' => $item->attr['href']
                            ];
                    }
                }
            }
        }
        catch (Exception $e) {}

        return $links;        
    }

    //Lista de metodos `para recuperar los titulos de las noticias
    public function getNoticiasAmbitoDestacados(){
        $titulos = [];

        try{
            $noticias = $this->html->find('.columna1y2', 0)->children;
            //sacamos el primer y los últimos 4 elementos ya que no son útiles
            unset($noticias[0]);
            unset($noticias[count($noticias)]);
            unset($noticias[count($noticias)]);
            unset($noticias[count($noticias)]);
            unset($noticias[count($noticias)]);

            if(!is_null($noticias)){                
                foreach($noticias as $item){
                    if(strpos($item->attr['class'], "itemresultado") !== false && !is_null($item->find('p', 0)->find('a', 0))){                        
                        $href = $item->find('p', 0)->find('a', 0);
                        array_push($titulos, $href->plaintext);
                    }
                }
            }
        }
        catch (Exception $e) {}

        return $titulos; 
    }

    public function getNoticiasElCiudadanoDestacados(){
        $titulos = [];

        try{
            $this->clearNode('.tapa-container');
            $this->clearNode('.banner');
            $this->clearNode('.full-image');

            $portada_items = $this->html->find('.wrapper', 0)->getElementsByTagName('h1, h3');            

            if(!is_null($portada_items)){                
                foreach($portada_items as $item){
                    if(!is_null($item->find('a', 0)) && !is_null($item->find('a', 0)->plaintext)){                        
                        $href = $item->find('a', 0);
                        array_push($titulos, $href->plaintext);
                    }
                }
            }

        }
        catch (Exception $e) {}

        return $titulos;
    }

    public function getNoticiasClarinDestacados($noticias){
        $titulos = [];

        try{
            foreach ($noticias->channel->item as $noticia) { 
                array_push($titulos, trim((string)$noticia->title));
            }
        }
        catch (Exception $e) {}

        return $titulos;
    }

    public function getNoticiasDiarioUnoDestacados(){
        $titulos = [];
        
        try{
            $this->clearNode('.main-header');
            $this->clearNode('.footer');
            $this->clearNode('.main-nav');
            $this->clearNode('.topic-nav');
            $this->clearNode('.article-ranking');
            
            //---- Ocasionalmente aparecen destacadas de dos columnas ----//
            $portada_destacadas = $this->html->find('section.widget-highlight',0);
            if(!is_null($portada_destacadas)){                
                foreach($portada_destacadas->find("article") as $article){
                    if(!is_null($article->find('.section-link', 0)) && !is_null($article->find('.title-item', 0))){
                        array_push($titulos, html_entity_decode($article->find('.title-item', 0)->find('a',0)->plaintext));
                    }
                }
            }
            //---- Fin Bloque inicial 3 notas ----//    
            
            // encuentro un solo articulo aca
            $portada_destacada = $this->html->find('div.region-biggest-item',0);
            if(!is_null($portada_destacada)){                
                foreach($portada_destacada->find("article") as $article){
                    if(!is_null($article->find('.section-link', 0)) && !is_null($article->find('.title-item', 0))){
                        array_push($titulos, html_entity_decode($article->find('.title-item', 0)->find('a',0)->plaintext));
                    }
                }
            }

            // dos bloques con articulos
            $portada_destacada = $this->html->find('div.region-big-items',0);
            if(!is_null($portada_destacada)){                
                foreach($portada_destacada->find("article") as $article){
                    if(!is_null($article->find('.section-link', 0)) && !is_null($article->find('.title-item', 0))){
                        array_push($titulos, html_entity_decode($article->find('.title-item', 0)->find('a',0)->plaintext));
                    }
                }
            }     
            
            // listas de articulos
            $noticias_home_lista = $this->html->find('div.simple-list');
            if(!is_null($noticias_home_lista)){  
                foreach($noticias_home_lista as $lista){
                    foreach($lista->find("article") as $article){
                        if(!is_null($article->find('.title-item', 0))){
                            if(!is_null($article->find('.section-link', 0))){
                                $seccion = $article->find('.section-link', 0)->plaintext;
                            }
                            else{
                                $url_array = explode("/", $article->find('.title-item', 0)->find('a',0)->href);
                                if(isset($url_array[3])){
                                    $seccion = $url_array[3];
                                }
                                else{
                                    continue;
                                }
                            }
                            array_push($titulos, html_entity_decode($article->find('.title-item', 0)->find('a',0)->plaintext));
                        }
                    }
                }
            }
            
                    
            //bloques de 4 notas
            $columnas_notas = $this->html->find('div.four-columns');
            if(!is_null($columnas_notas)){ 
                foreach($columnas_notas as $columna){
                    foreach($columna->find("article") as $article){
                        if(!is_null($article->find('.title-item', 0))){
                            if(!is_null($article->find('.section-link', 0))){
                                $seccion = $article->find('.section-link', 0)->plaintext;
                            }
                            else{
                                $url_array = explode("/", $article->find('.title-item', 0)->find('a',0)->href);
                                if(isset($url_array[3])){
                                    $seccion = $url_array[3];
                                }
                                else{
                                    continue;
                                }
                            }
                            array_push($titulos, html_entity_decode($article->find('.title-item', 0)->find('a',0)->plaintext));
                        }
                    }
                }
            }
            
            $slider_notas = $this->html->find('div.owl-wrapper',0);
            if(!is_null($slider_notas)){
                foreach($slider_notas->find("article") as $article){
                    if(!is_null($article->find('.title-item', 0))){
                        $url_array = explode("/", $article->find('.title-item', 0)->find('a',0)->href);
                        if(isset($url_array[3])){
                            $seccion = $url_array[3];
                        }
                        else{
                            continue;
                        }
                        array_push($titulos, html_entity_decode($article->find('.title-item', 0)->find('a',0)->plaintext));
                    }
                }
            }
        }
        catch (\Exception $ex) {}

        return $titulos;
    }

    public function getNoticiasElSolDestacados(){
        $titulos = [];

        try{
            $links_items = $this->html->find('.notas-destacadas');
            if(!is_null($links_items)){                
                foreach($links_items as $item){
                    foreach($item->find('a') as $link){
                        if($link->plaintext && !in_array($link->plaintext, $titulos) && str_word_count($link->plaintext) > 1){
                            array_push($titulos, $link->plaintext);
                        }
                    }
                }
            }
        }
        catch (Exception $e) {}

        return $titulos;
    }

    public function getNoticiasInfobaeDestacados($noticias){
        $titulos = [];

        try{
            foreach ($noticias->channel->item as $noticia) { 
                array_push($titulos, trim((string)$noticia->title));
            }
        }
        catch (Exception $e) {}

        return $titulos;
    }

    public function getNoticiasLaNacionDestacados(){
        $titulos = [];

        try{
            $links_header_grande = $this->html->find('#apertura', 0);
            if(!is_null($links_header_grande)){
                $links_header_grande = $links_header_grande->find('h2');
                if(!is_null($links_header_grande)){
                    foreach($links_header_grande as $item_grande){
                        if(!is_null($item_grande->find('a', 0))){
                            array_push($titulos, $item_grande->find('a', 0)->plaintext);
                        }
                    }
                }
            }

            $links_header_chico = $this->html->find('#mosaico', 0);
            if(!is_null($links_header_chico)){
                $links_header_chico = $links_header_chico->find('h2');
                if(!is_null($links_header_chico)){
                    foreach($links_header_chico as $item_chico){
                        if(!is_null($item_chico->find('a', 0))){
                            array_push($titulos, $item_chico->find('a', 0)->plaintext);
                        }
                    }
                }
            }

            $links_body = $this->html->find('#mosaico-acu', 0);
            if(!is_null($links_body)){
                $links_body = $links_body->find('h2');
                if(!is_null($links_body)){
                    foreach($links_body as $item_body){
                        if(!is_null($item_body->find('a', 0))){
                            array_push($titulos, $item_body->find('a', 0)->plaintext);
                        }
                    }
                }
            }
        }
        catch (Exception $e) {}

        return $titulos;
    }

    public function getNoticiasLosAndesDestacados($noticias){
        $titulos = [];

        try{
            foreach ($noticias->channel->item as $noticia) { 
                array_push($titulos, trim((string)$noticia->title));
            }
        }
        catch (Exception $e) {}

        return $titulos;
    }

    public function getNoticiasMdzDestacados(){
        $titulos = [];
        
        try{
            $links_items = $this->html->find('#items', 0)->find('h2');
            if(!is_null($links_items)){
                foreach($links_items as $item){
                    $href = $item->find('a', 0);
                    if(!is_null($href->plaintext)){
                        array_push($titulos, $href->plaintext);
                    }
                }
            }
        }
        catch (Exception $e) {}

        return $titulos;
    }

    public function getNoticiasMendozaPostDestacados(){
        $titulos = [];
        
        try{
            $this->clearNode('.header-wrapper');            
            $this->clearNode('.menu-wapper');            
            $this->clearNode('.footer-wrapper');
            $this->clearNode('.banner');
            $this->clearNode('.separator');
            $this->clearNode('script');
            
            $portada_items = $this->html->find('.section-title');
            if(!is_null($portada_items)){                
                foreach($portada_items as $article){
                    if(!is_null($article->find('.title', 0)) && !is_null($article->find('.title', 0)->find('a', 0))){
                        array_push($titulos, html_entity_decode($article->find('.title', 0)->find('a', 0)->plaintext));
                    }
                }
            }
            
        } 
        catch (Exception $e) {}
        
        return $titulos;
    }

    public function getNoticiasMinutoUnoDestacados(){
        $titulos = [];
        
        try{
            $links_items = $this->html->find('.tag-list', 0);
            if(!is_null($links_items)){
                $links_items = $links_items->find('.content', 0);
                foreach($links_items as $item){
                    if($item->tag == "a" && !is_null($item->attr['href'])){
                        array_push($titulos, $item->attr['href']);
                    }
                }
            }
        }
        catch (Exception $e) {}

        return $titulos;
    }

    public function getNoticiasPagina12Destacados($noticias){
        $titulos = [];

        try{
            foreach ($noticias->channel->item as $noticia) { 
                array_push($titulos, trim((string)$noticia->title));
            }
        }
        catch (Exception $e) {}

        return $titulos;
    }

    public function getNoticiasSitioAndinoDestacados(){
        $titulos = [];
        
        try{
            $this->clearNode('.header-wrapper');
            $this->clearNode('.alerta');
            $this->clearNode('.menu-wapper');
            $this->clearNode('.ranking');
            $this->clearNode('.footer-wrapper');
            $this->clearNode('.banner');
            $this->clearNode('.separator');
            $this->clearNode('.share-buttons');
            $this->clearNode('script');

            $destacados_items = $this->html->find('.hightlighted-items', 0);
            if(!is_null($destacados_items)){                
                $destacados = $destacados_items->find('a');
                foreach($destacados as $destacado){
                    array_push($titulos, $destacado->attr['title']);
                }
            }
            
            $portada_items = $this->html->find('.item');
            if(!is_null($portada_items)){                
                foreach($portada_items as $article){
                    if(!is_null($article->find('.title', 0)) && !is_null($article->find('.title', 0)->find('a',0))){
                        array_push($titulos, html_entity_decode($article->find('.title', 0)->find('a',0)->plaintext));
                    }
                }
            }
        }
        catch (Exception $e) {}

        return $titulos;
    }

    public function getNoticiasTelamDestacados(){
        $titulos = [];
        
        try{
            $links_items = $this->html->find('.wrapper-ampliado', 0)->find('.main-box');
            $links_items = end($links_items);
            if(!is_null($links_items)){
                foreach($links_items->children as $item){
                    if(!is_null($item->find('h3', 0))){
                        array_push($titulos, $item->find('h3', 0)->plaintext);
                    }
                }
            }
        }
        catch (Exception $e) {}

        return $titulos;
    }
}