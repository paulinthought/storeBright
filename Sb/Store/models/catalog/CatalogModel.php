<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class CatalogModel extends ParentModel
{

    public function __construct()
    {

        parent::__construct();

        echo 'Loaded Catalog Model';
        var_dump($this);
        echo 'End Catalog Model';
    }
    
    public function addItem($item)
    {
        //$this->StoreSession
    }
}
?>
