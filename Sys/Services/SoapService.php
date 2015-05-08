<?php

namespace Sys;

/*
 * TODO: install http2_request from composer and make this class use that instead
 * and open the template in the editor.
 */

/**
 * Description of SoapService
 *
 * @author PG
 */
class SoapService extends \Sys\Services {

    private $curlHandle;
    private $request;
    private $url;
    public $curlOptions = array(CURLOPT_POST => true);

    public function __construct($url) {
        parent::__construct();
        $this->url = $url;
        // createResource needs to be moved to have url and opts going into it.
        // $url shouldn't be passed in constructor
    }

    public function setSoapRequest($url, $xml) {
        $this->url = $url;
        $this->request = $xml;
        $this->curlOptions[CURLOPT_URL] = $this->url;
        $this->curlOptions[CURLOPT_POSTFIELDS] = $xml;
        $this->curlOptions[CURLOPT_HTTPHEADER] = array('Content-Type: text/xml; charset=utf-8', 'Content-Length: ' . strlen($xml));

        $this->curlHandle = $this->createResource($this->url, $this->curlOptions);

        $this->getServiceDefinition();
        return $this;
    }

    public function getUrl() {
        return $this->url;
    }

    private function getServiceDefinition() {
        echo 'curling ';
        foreach ($this->getServiceDetails() as $detail)
            if ($detail['url'] == $this->url)
                return ($detail);
    }

}
