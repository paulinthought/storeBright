<?php
namespace Sys;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sysutils
 *
 * @author PG
 */
class Sysutils {
    
    static public function Ucase($string)
    {
        return ucfirst(strtolower($string));
    }
}
