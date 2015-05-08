<?php
namespace Sys;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Caching
 *
 * @author PG
 */
class Caching {

    protected $_parent;
    protected $cacheType = '';
    protected $cacheLocation = '';
    protected $memcache;
    
    public function __construct($Sb)
    {
        echo 'CACH';
        $this->_parent = $Sb;
        foreach ($this->_parent->getConfig('caching') as $cacheEntry)
            $this->cache[$cacheEntry->type] = $cacheEntry->location;
        
//        if(isset($this->cache['memcache']))
//            $this->memcache = new \Memcache();
        
        var_dump($this->cache);
        echo 'endCACH';
    } 
    
    public function checkCache($obj)
    {
        // check if object is in cache
    }
    
    public function cacheObject($key, $value, $serialize = false)
    {
        // write the object to cache
        if ($serialize) serialize(&$value);
        $result = $this->memcache->replace( $key, $value ); 
        if( $result == false ) 
        { 
            $result = $this->memcache->set( $key, $value ); 
        } 
    } 
    
    public function getObject($key, $deserialize = false)
    {
        // get the object from cache
        if (!$deserialize)
        {
            return deserialize($this->memcache->get($key));
        }
        return $this->memcache->get($key);
    }  
    
    /* For Memcached 
    public function get($key, $callback = null) {
        if (isset($callback))
           return $this->memcache->get($key, $callback);
        else
            return $this->memcache->get($key);
    }
     *
     */
}
