<?php
namespace Sys;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Services
 *
 * @author PG
 */
class Services {
    
    public $urls;
    private $mcHandle;
    
    public function __construct() 
    {    
        $this->mcHandle = curl_multi_init();     
    }
    
    public function getServiceUrls()
    {
        return $this->urls;
    }
    
    public function getServiceDetails()
    {
        return \Sys\Initializer::$autoLoadedServices;
    }
    
    public function invoke()
    {
        $active = null;
        //execute the handles
        do {
            $mrc = curl_multi_exec($this->mcHandle, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($this->mcHandle) != -1) {
                do {
                    $mrc = curl_multi_exec($this->mcHandle, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }
        
        return $mrc;
    }
    
    public function getResponse($ch){
        
        curl_multi_getcontent( $ch );
    }
    
    public function createResource($url, $curlopts = array())
    {
        $ch = curl_init($url);
        $this->urls[] = array('ch' => $ch, 'url' => $url);
        if (!empty($curlopts))
        {
            curl_setopt_array($ch, $curlopts);
        }
        curl_multi_add_handle($this->mcHandle,$ch);
        
        return $ch;
    }
    
    public function __destruct() 
    {
        try {
            curl_multi_close($this->mcHandle);
        } catch (\Exception $e) 
        {
            // Log couldn't close curl, presumably because we couldn't open it 1st
        }
    }
}
