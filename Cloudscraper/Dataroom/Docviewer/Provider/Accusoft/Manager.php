<?php
namespace Library\Cloudscraper\Dataroom\Docviewer\Provider\Accusoft;

class Manager extends \Library\System\Manager\AbstractManager
{

    protected $_config = NULL;

    /**
     * Sets config.
     *
     * @param unknown $config
     * @return \Library\Cloudscraper\Dataroom\Docviewer\Provider\Accusoft\Manager
     */
    public function setConfig($config)
    {
        $this->_config = $config;
        return $this;
    }

    /**
     * Returns config.
     *
     * @return unknown
     */
    public function getConfig()
    {
        if ($this->_config == NULL) {
            $this->setConfig($this->getDi()
                ->get("config")->docViewer);
        }
        
        return $this->_config;
    }

    /**
     *
     * @var $_httpClient;
     */
    protected $_httpClient = NULL;

    public function setHttpClient($httpClient)
    {
        $this->_httpClient = $httpClient;
        return $this;
    }

    public function getHttpClient()
    {
        if ($this->_httpClient == NULL) {
            $this->setHttpClient($this->getDI()
                ->get("httpClient"));
        }
        
        return $this->_httpClient;
    }


    public function getDocViewerWebServiceUrl()
    {
        $config = $this->getConfig();
        return "http://" . $config['webServiceHost'] . ':' . $config['webServicePort'] . '/' . $config['webServicePath'];
    }

    public function getViewingSession($documentPath)
    {
        $isp = isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : gethostbyaddr($_SERVER["REMOTE_ADDR"]);
        
        $data = [
            // Store some information in PCCIS to be retrieved later.
            'externalId' => sha1($documentPath),
            'tenantId' => 'My User ID',
            'origin' => [
                'ipAddress' => $_SERVER['REMOTE_ADDR'],
                'hostName' => $isp,
                'sourceDocument' => $documentPath
            ],
            // Specify rendering properties.
            'render' => [
                'flash' => [
                    'optimizationLevel' => 1
                ],
                'html5' => [
                    'alwaysUseRaster' => false
                ]
            ]
        ];
        
        $url = $this->getDocViewerWebServiceUrl() . '/ViewingSession';
        
        $header = Array(
            "Content-Type: application/json",
            "Accept: application/json",
            "Accusoft-Affinity-Hint: $documentPath"
        );
        
        $httpClient = $this->getHttpClient();
        $response = $httpClient->post($url, json_encode($data), true, $header);
        if ($response instanceof \Phalcon\Http\Client\Response) {
            $response = $response->body;
            $response = json_decode($response);
            return $response->viewingSessionId;
        }
        
        return false;
    }

    public function getTemplateFiles($templateFilePath)
    {
        $config = $this->getConfig();
        $templateFiles = $config['templateFiles'];
        $tpls = [];
        foreach ($templateFiles as $filename) {
            $tplName = str_ireplace('template.html', '', $filename);
            $tplFile = preg_replace("/\s+/", " ", file_get_contents($templateFilePath . $filename));
            $tpls[$tplName] = $tplFile;
        }
        return $tpls;
    }

    // Check for a language.json file, and load it if one exists. This is
    // used by the HTML5 viewer to allow user customization of the various
    // text strings it uses for buttons, tooltips and menu items.
    public function getLanguageElements($languageFilePath)
    {
        $languageElements = "{}";
        if (file_exists($languageFilePath)) {
            $languageElements = file_get_contents($languageFilePath);
        }
        return $languageElements;
    }

    // Check for a predefinedSearch.json file, and load it if one exists. This is
    // used by the HTML5 viewer to allow user customization of the various
    // predefined search options.
    public function getPredefinedSearch($predefinedSearchJSONPath)
    {
        $predefinedSearch = "{}";
        if (file_exists($predefinedSearchJSONPath)) {
            $predefinedSearch = file_get_contents($predefinedSearchJSONPath);
        }
        
        return $predefinedSearch;
    }

    /*
     * Check for a redactionReason.json file, and load it if one exists. This is
     * used by the HTML5 viewer to allow user to add text explanations to
     * redacted areas.
     */
    public function getRedactionReasons($redactionReasonsJSONPath)
    {
        $redactionReasons = "{}";
        if (file_exists($redactionReasonsJSONPath)) {
            $redactionReasons = file_get_contents($redactionReasonsJSONPath);
        }
        return $redactionReasons;
    }

    public function uploadDocumentToViewerServer($documentPath, $viewingSessionId)
    {
        $httpClient = $this->getHttpClient();
        if (! file_exists($documentPath)) {
            throw new AccusoftDocviewerManagerException("The document you are trying to upload to the viewer server cannot be located. ");
        }
        $config = $this->getConfig();
        $fileExtension = preg_replace("/(.*\.)/", "", $documentPath);
        $url = $this->getDocViewerWebServiceUrl() . "/ViewingSession/u$viewingSessionId/SourceFile?FileExtension=$fileExtension";
        $header = Array(
            "Content-Type: application/json"
        );
        $fileContents = file_get_contents($documentPath);
        $response = $httpClient->put($url, $fileContents, true, $header);
        return $response;
    }

    /**
     * Notifies prizmdoc viewer server about the start of session.
     */
    public function sendSessionStartedNotification($viewingSessionId)
    {
        $httpClient = $this->getHttpClient();
        
        $data = array(
            'viewer' => 'HTML5'
        );
        
        $url = $this->getDocViewerWebServiceUrl() . "/ViewingSession/u$viewingSessionId/Notification/SessionStarted";
        $header = Array(
            "Content-Type: application/json",
            "Accept: application/json"
        );
        
        $response = $httpClient->post($url, json_encode($data), true, $header);
        if ($response instanceof \Phalcon\Http\Client\Response) {
            $response = $response->body;
            $response = json_decode($response);
        }
    }
    
    /**
    * 
    *@var  $_viewingSessionId;
    */
    protected $_viewingSessionId;
    
    public function setViewingSessionId($viewingSessionId){
        $this->_viewingSessionId =$viewingSessionId;
        return $this;
    }
    
    public function getViewingSessionId(){
        return $this->_viewingSessionId ;
    }
    
    public function saveDocument(){
        $urlViewingSessionId = $this->getViewingSessionId();
        $url = $this->getDocViewerWebServiceUrl() . "/ViewingSession/$urlViewingSessionId";
        $httpClient = $this->getHttpClient();
        $response = $httpClient->get($url);
        if ($response instanceof \Phalcon\Http\Client\Response) {
            $response = json_decode($response->body);
            $sourceFile = $response->origin->sourceDocument;
            $headers = Array(
                'Content-Description: File Transfer',
                'Content-Type: application/octet-stream',
                "Content-Disposition: attachment; filename='{basename($sourceFile)}'",
                'Content-Transfer-Encoding: binary',
                'Expires: 0',
                'Cache-Control: must-revalidate, post-check=0, pre-check=0',
                'Pragma: public',
                'Access-Control-Allow-Origin: *'
                );
            $url = $this->getDocViewerWebserviceUrl() . "/ViewingSession/$urlViewingSessionId/SourceFile";
            
            $finalResponse = $httpClient->get($url, $headers);
            if ($finalResponse instanceof \Phalcon\Http\Client\Response) {
                return [
                    "content" => $finalResponse->body,
                    "sourceFileName" => $sourceFile
                ];
            }
            
            return false;
        }
    }
    
    public function sendDocumentViewRequest($requestUrl){
        $imagingServiceUri = $this->getDocViewerWebServiceUrl() . $requestUrl;
        
        $httpClient = $this->getHttpClient();
        $response = $httpClient->get($imagingServiceUri);
        
        if ($response instanceof \Phalcon\Http\Client\Response) {
            $response = $response->body;
        }
        
        return $response;
    }
}

class AccusoftDocviewerManagerException extends \Phalcon\Exception
{
}