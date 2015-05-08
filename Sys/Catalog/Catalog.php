<?php
namespace Sys;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Catalog
 *
 * @author PG
 */
class Catalog {
     
    protected $_parent;
    
    public function __construct($Sb)
    {
        $this->_parent = $Sb;
        echo 'CATALOG';
    }          
}