<?php
namespace Library\System\Controller;

class ApiAbstractController extends \Phalcon\Mvc\Controller
{
    
    public $response;
    
    protected $_authorizationToken;
    
    public function initialize()
    {
        // $this->request = new \Library\System\Request\Manager();
        $this->response = new \Library\System\Response\Manager();
    }
    
    /**
     * @quarantine
     * @param string $apiurl
     * @throws \ErrorException
     * @return mixed
     */
    public function getApiResponse($endpoint, $params = [], $apiurl = null)
    {
        if ($apiurl == NULL) {
            $apiurl = $this->getDi()->get("config")->application->apiUrls->cloudscraper->url . $endpoint;
        }
        $httpClient = $this->getDi()->get("httpClient");
        $httpClient->setOption("CURLOPT_SSL_VERIFYHOST", 0);
        $httpClient->setOption('CURLOPT_IPRESOLVE','CURL_IPRESOLVE_V4');
        $httpClient->setOption("CURLOPT_SSL_VERIFYPEER", 0);
        $response = $httpClient->get($apiurl, $params);
        $responseBody = json_decode($response->body,true);
    
        if($responseBody == null){
            throw new \Exception($response->body);
        }
        
        if($responseBody['success']){
            return $responseBody['data'];
        }
        
        return $responseBody;
        
    }
    
    /**
     *
     * @param string $apiurl
     * @throws \ErrorException
     * @return mixed
     */
    public function sendApiRequest($endpoint, $params = [], $requestMethod = 'get', $apiurl = null)
    {
        if ($apiurl == NULL) {
            $apiurl = $this->getDi()->get("config")->application->apiUrls->cloudscraper->url . $endpoint;
        }
        $httpClient = $this->getDi()->get("httpClient");
        $httpClient->setOption("CURLOPT_SSL_VERIFYHOST", 0);
        $httpClient->setOption("CURLOPT_SSL_VERIFYPEER", 0);
        $curl = new \Phalcon\Http\Client\Provider\Curl();
        switch ($requestMethod) {
            case 'POST':
                $response = $httpClient->post($apiurl, $params);
                break;
            default:
                $response = $httpClient->get($apiurl, $params);
        }
        
        return $response;
    }
    
    /**
     * Returns api response from data
     * @param unknown $response
     * @param boolean $jsonDecode
     * @return mixed|string|boolean
     */
    public function getDataFromApiResponse($response,$jsonDecode=true){
        if($response instanceof \Phalcon\Http\Client\Response){
            if($response->header->statusCode == 200){
                $body = $response->body;
                
                if($jsonDecode){
                    return json_decode($body,true);
                }
                
                return $body;
            }
        }
    }
}