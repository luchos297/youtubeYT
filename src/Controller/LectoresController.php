<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Exception\Exception;

class LectoresController extends AppController{

    public $articulosTable;
    public $rssesTable;   

    public function initialize(){
        parent::initialize();
        // Crawler portales nacionales
        $this->loadComponent('AmbitoCrawler');
        $this->loadComponent('ClarinCrawler');
        $this->loadComponent('InfobaeCrawler');
        $this->loadComponent('LaNacionCrawler');
        $this->loadComponent('MinutoUnoCrawler');
        $this->loadComponent('Pagina12Crawler');
        $this->loadComponent('TelamCrawler');
        // Crawler portales locales
        $this->loadComponent('UnoCrawler');
        $this->loadComponent('ElCiudadanoCrawler');
        $this->loadComponent('ElSolCrawler');
        $this->loadComponent('LosAndesCrawler');
        $this->loadComponent('MDZCrawler');
        $this->loadComponent('MendozaPostCrawler');
        $this->loadComponent('SitioAndinoCrawler');
        // Crawler portales sociales
        $this->loadComponent('UnoSocialesCrawler');
        $this->loadComponent('ElSolSocialesCrawler');
        $this->loadComponent('MDZSocialesCrawler');
        $this->loadComponent('LosAndesSocialesCrawler');
        $this->loadComponent('LosAndesProvincialesCrawler');
        // Crawler portales de revistas
        $this->loadComponent('CanchaLlenaCrawler');
        $this->loadComponent('CarasCrawler');
        $this->loadComponent('LookCrawler');
        $this->loadComponent('ClubHouseCrawler');
        $this->loadComponent('WeekendCrawler');
        //$this->loadComponent('Cookie');
    }

    function beforeFilter(\Cake\Event\Event $event){
        $this->Auth->allow([
            'ejecutarPortalesNacionales',
            'ejecutarPortalesLocales',
            'ejecutarPortalesSociales',
            'ejecutarRevistas'
        ]);
        $this->viewBuilder()->layout('cms');
    }

    function index(){}

    function ejecutarPortalesNacionales(){
        $resultados = [];
        $resultados[] = $this->_leer_ambito();
        $resultados[] = $this->_leer_clarin();
        $resultados[] = $this->_leer_infobae();
        $resultados[] = $this->_leer_la_nacion();
        $resultados[] = $this->_leer_minuto_uno();
        $resultados[] = $this->_leer_pagina12();
        $resultados[] = $this->_leer_telam();
        $this->set('guardados', $resultados);
    }

    function ejecutarPortalesLocales(){
        $resultados = [];
        $resultados[] = $this->_leer_uno_mza();
        $resultados[] = $this->_leer_el_ciudadano();
        $resultados[] = $this->_leer_el_sol();
        $resultados[] = $this->_leer_los_andes();
        $resultados[] = $this->_leer_mdz();
        $resultados[] = $this->_leer_mendoza_post();
        $resultados[] = $this->_leer_sitio_andino();
        $this->set('guardados', $resultados);
    }

    function ejecutarPortalesSociales(){
        $resultados = [];
        $resultados[] = $this->_leer_uno_sociales_mza();
        $resultados[] = $this->_leer_el_sol_sociales();
        $resultados[] = $this->_leer_mdz_sociales();
        $resultados[] = $this->_leer_los_andes_sociales();
        $resultados[] = $this->_leer_los_andes_provinciales();
        $this->set('guardados', $resultados);
    }

    function ejecutarRevistas(){
        $resultados = [];
        $resultados[] = $this->_leer_cancha_llena();
        $resultados[] = $this->_leer_caras();
        $resultados[] = $this->_leer_look();
        $resultados[] = $this->_leer_club_house();
        $resultados[] = $this->_leer_weekend();
        $this->set('guardados', $resultados);
    }

    function _leer_ambito(){
        try{
            $this->AmbitoCrawler->setCodigo('AMBITO');
            return $this->AmbitoCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['AMBITO' => [$e]];
        }
    }

    function _leer_clarin(){
        try{
            $this->ClarinCrawler->setCodigo('CLARIN');
            return $this->ClarinCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['CLARIN' => [$e]];
        }
    }

    function _leer_infobae(){
        try{
            $this->InfobaeCrawler->setCodigo('INFOBAE');
            return $this->InfobaeCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['INFOBAE' => [$e]];
        }
    }

    function _leer_la_nacion(){
        try{
            $this->LaNacionCrawler->setCodigo('LANACION');
            return $this->LaNacionCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['LANACION' => [$e]];
        }
    }

    function _leer_minuto_uno(){
        try{
            $this->MinutoUnoCrawler->setCodigo('MINUTOUNO');
            return $this->MinutoUnoCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['MINUTOUNO' => [$e]];
        }
    }

    function _leer_pagina12(){
        try{
            $this->Pagina12Crawler->setCodigo('PAGINA12');
            return $this->Pagina12Crawler->runCrawler();
        }
        catch(Exception $e){
            return ['PAGINA12' => [$e]];
        }
    }

    function _leer_telam(){
        try{
            $this->TelamCrawler->setCodigo('TELAM');
            return $this->TelamCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['TELAM' => [$e]];
        }
    }

    function _leer_uno_mza(){
        try{
            $this->UnoCrawler->setCodigo('DIARIOUNO');
            return $this->UnoCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['DIARIOUNO' => [$e]];
        }
    }

    function _leer_el_ciudadano(){
        try{
            $this->ElCiudadanoCrawler->setCodigo('CIUDADANO');
            return $this->ElCiudadanoCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['CIUDADANO' => [$e]];
        }
    }

    function _leer_el_sol(){
        try{
            $this->ElSolCrawler->setCodigo('ELSOL');
            return $this->ElSolCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['ELSOL' => [$e]];
        }
    }

    function _leer_los_andes(){
        try{
            $this->LosAndesCrawler->setCodigo('LOSANDES');
            return $this->LosAndesCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['LOSANDES' => [$e]];
        }
    }

    function _leer_mdz(){
        try{
            $this->MDZCrawler->setCodigo('MDZ');
            return $this->MDZCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['MDZ' => [$e]];
        }
    }

    function _leer_mendoza_post(){
        try{
            $this->MendozaPostCrawler->setCodigo('MENDOZAPOST');
            return $this->MendozaPostCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['MENDOZAPOST' => [$e]];
        }
    }

    function _leer_sitio_andino(){
        try{
            $this->SitioAndinoCrawler->setCodigo('SITIOANDINO');
            return $this->SitioAndinoCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['SITIOANDINO' => [$e]];
        }
    }

    function _leer_uno_sociales_mza(){
        try{
            $this->UnoSocialesCrawler->setCodigo('DIARIOUNO_SOCIALES');
            return $this->UnoSocialesCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['DIARIOUNO_SOCIALES' => [$e]];
        }
    }

    function _leer_el_sol_sociales(){
        try{
            $this->ElSolSocialesCrawler->setCodigo('ELSOL_SOCIALES');
            return $this->ElSolSocialesCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['ELSOL_SOCIALES' => [$e]];
        }
    }

    function _leer_mdz_sociales(){
        try{
            $this->MDZSocialesCrawler->setCodigo('MDZ_SOCIALES');
            return $this->MDZSocialesCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['MDZ_SOCIALES' => [$e]];
        }
    }

    function _leer_los_andes_sociales(){
        try{
            $this->LosAndesSocialesCrawler->setCodigo('LOSANDES_SOCIALES');
            return $this->LosAndesSocialesCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['LOSANDES_SOCIALES' => [$e]];
        }
    }

    function _leer_los_andes_provinciales(){
        try{
            $this->LosAndesProvincialesCrawler->setCodigo('LOSANDES_PROVINCIALES');
            return $this->LosAndesProvincialesCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['LOSANDES_PROVINCIALES' => [$e]];
        }
    }

    function _leer_cancha_llena(){
        try{
            $this->CanchaLlenaCrawler->setCodigo('CANCHALLENA');
            $this->CanchaLlenaCrawler->setCategoria('DEPORTES');
            return $this->CanchaLlenaCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['CANCHALLENA' => [$e]];
        }
    }

    function _leer_caras(){
        try{
            $this->CarasCrawler->setCodigo('CARAS');
            $this->CarasCrawler->setCategoria('CARAS');
            return $this->CarasCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['CARAS' => [$e]];
        }
    }

    function _leer_look(){
        try{
            $this->LookCrawler->setCodigo('LOOK');
            $this->LookCrawler->setCategoria('LOOK');
            return $this->LookCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['LOOK' => [$e]];
        }
    }

    function _leer_club_house(){
        try{
            $this->ClubHouseCrawler->setCodigo('LOSANDES');
            $this->ClubHouseCrawler->setCategoria('MCH');
            return $this->ClubHouseCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['MCH' => [$e]];
        }
    }

    function _leer_weekend(){
        try{
            $this->WeekendCrawler->setCodigo('WEEKEND');
            $this->WeekendCrawler->setCategoria('WEEKEND');
            return $this->WeekendCrawler->runCrawler();
        }
        catch(Exception $e){
            return ['WEEKEND' => [$e]];
        }
    }
}