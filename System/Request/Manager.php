<?php
namespace Library\System\Request;
class Manager extends \Phalcon\Http\Client\Provider\Curl{
    protected $_url = '';
    
    /**
     * Set url.
     * @param string $url
     * @return \Library\System\Request\Manager
     */
    public function setUrl($url){
        $this->_url = $url;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getUrl(){
        if(empty($this->_url)){
            $di = \Phalcon\DI::getDefault();
            $this->setUrl($di->get("config")->application->apiUrls->cloudscraper->url);
        }
        
        return $this->_url;
    }
    
    public function get($endpoint,$params){
        $this->setOption("CURLOPT_SSL_VERIFYHOST", 0);
        $this->setOption('CURLOPT_IPRESOLVE','CURL_IPRESOLVE_V4');
        $this->setOption("CURLOPT_SSL_VERIFYPEER", 0);
        $response = parent::get($this->getUrl().$endpoint, $params);
        $responseBody = json_decode($response->body,true);
        
        if($responseBody == null){
            throw new \Exception($response->body);
        }
        
        if($responseBody['success']){
            return $responseBody['data'];
        }
        
        return $responseBody;
    }
    
}