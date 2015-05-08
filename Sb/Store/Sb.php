<?php
namespace Store;
use Sys\Initializer as Ini;
/*
 * Store Sb to be instantiated in the index.php
 */

/**
 * This is the root class through which all objects and services, modules etc. should be made available
 * Services class is instantiated in Sb\Sys to be made available through injection into other sys level module classes
 *
 * @author PG
 */
class Sb {
    
    private $sysSb;
    public $services = array();
    public $modules = array();
    /**
     * 
     * @param Object \Sys\$Sb
     */
    public function __construct($Sb)
    {
        $this->sysSb = $Sb;
        
        $this->loadModules();
        
        // Instantiate the services in Sb\Sys
        if (!empty(Ini::$serviceConnections))
            $this->sysSb->createServiceObjects(); 

        $this->getServicesAndModules();
                
    }

    /**
     * loads the Sys available modules as defined in config.json such as "catalog", "sessions", "caching" for example
     * Basically anything in a subfolder in Sys except services
     */
    private function loadModules()
    {
        //echo 'loading modules ';var_dump(Ini::$storeModules);         
        foreach (Ini::$storeModules as $module)
        {
            $className = \Sys\Sysutils::Ucase($module);
            $fqClassname = "\\Sys\\".$className;
            Ini::loadModule(\Sys\Sysutils::Ucase($module), \Sys\Sysutils::Ucase($module), '\Sys');
            Ini::$autoLoadedClasses[$className] = $fqClassname;  
        }

    }

    private function getServicesAndModules()
    {
        // Get a copy of them down into here
        foreach ($this->sysSb->autoLoadedServices as $className => $fqClassName)
        { 
            $this->services[$className] = $this->sysSb->getService($className);
        } 
        
        // Instantiate Sys module classes, sessions, caching etc.
        foreach (Ini::$autoLoadedClasses as $className => $fqClassName)
        { 
            $this->modules[$className] = new $fqClassName($this->sysSb);
        }        
    }

    public function showServices() {
        return $this->services;
    }

    public function showModules() {
        return $this->modules;
    }    
}
