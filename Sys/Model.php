<?php
namespace Sys;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model
 *
 * @author PG
 */
Abstract class Model {

    public $Sb;
    
    public function __construct() {
        
        echo 'Loaded Abstract SysModel';
        $this->Sb = \Sys\Initializer::getSb();
        var_dump($this);
        echo 'End Abstract SysModel';
    }
    

}

?>
