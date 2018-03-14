<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\View\Helper;

use Cake\View\Helper;

class TextoHelper extends Helper
{
    public function limitarTexto($string = "", $inicio = 0, $limite = 0)
    {
        $texto = (strlen(strip_tags($string)) > $limite) ? substr(strip_tags($string),$inicio,$limite).'...' : strip_tags($string); "";
        return $texto;
    }
}