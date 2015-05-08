<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ParentController extends \Sb\Sys\Controller
{

    public function __construct()
    {
        parent::__construct();
        echo 'Loaded Parent Controller';
        var_dump($this);
        echo 'End Parent Controller<br>';
    }
}
?>
