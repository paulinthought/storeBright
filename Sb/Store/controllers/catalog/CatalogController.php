<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class CatalogController extends ParentController
{
    public function __construct()
    {
	parent::__construct();
        
        echo 'Loaded Catalog Controller';
        var_dump($this);
        echo 'End Catalog Controller<br>';
    }
}
?>
