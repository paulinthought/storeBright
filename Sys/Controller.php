<?php
namespace Sys;

/**
 * Root controller class, parent of parentController
 *
 * @author PG
 */
Abstract class Controller {
    
    public $Sb; //This shouldn't really be here in a proper mvc since it exposes the data layer but sod it.
    public $model;
    
    public function __construct() {
        $this->Sb = \Sys\Initializer::getSb();
        $this->model = \Sys\Initializer::getModel();
    }
    
}

?>
