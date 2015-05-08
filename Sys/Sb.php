<?php

namespace Sys;
use Sys\Initializer as Ini;

/**
 * Sb is the main interface to the system and loads service classes .
 *
 * @author PG
 */
class Sb {

    public $autoLoadedServices = array(); // array of names of service connection objects, useful for getting the service object    
    public $storeName;
    
    public function __construct() {

        $this->storeName = Ini::$storeName;
        Ini::loadModule('Services', 'Services', '\Sys');
             
    }   
    
    /**
     * 
     * @param type string $serviceName
     * @return type Object 
     */
    public function getService($serviceName) 
    {
        if (isset($this->{$serviceName}))
            return $this->{$serviceName};
    }
    
    /**
     * Load the default classes instantiated in initializer into this class
     */
    public function createServiceObjects() 
    {
        var_dump('jj', Ini::$serviceConnections);
        //if (!empty(Ini::$serviceConnections)) 
        //{
            foreach (Ini::$serviceConnections as $connectionName => $connectionDetail) 
            {
                Ini::loadModule(Sysutils::Ucase($connectionDetail->type) . 'Service', 'Services', '\Sys');
                $fqClassname = '\\Sys\\'. Sysutils::Ucase($connectionDetail->type) . 'Service';
                $this->autoLoadedServices[$connectionName] = array('url' => $connectionDetail->url, 'type' => $connectionDetail->type);
                $this->{$connectionName} = new $fqClassname($connectionDetail->url);
            }
            
        //}
    }

    /**
     * Wrapper on the Ini::getConfig method
     * @param type $key
     * @return type
     */
    static public function getConfig($key = null)
    {
       return Ini::getConfig($key);
    }    
}
