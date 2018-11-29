<?php
namespace Library\System\Controller;

class WebAbstractController extends \Library\System\Controller\ApiAbstractController
{

    public $response;

    protected $_authorizationToken;

    protected $accountId;

    protected $userId;

    public function initialize()
    {
        $this->response = new \Library\System\Response\Manager();
        $this->authorize();
        
        if($this->request->isAjax()){
            $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
            $view = $this->request->get("view",null,false);
           
            if($view!= false){
                $this->view->pick($view);
                $this->view->picked = true;
            }
        }else{
            $this->loadDefaultAssets();
        }
    }

    /**
     * Authorizes each action.
     */
    public function authorize()
    {
        if (! isset($_COOKIE['cloudscraper'])) {
            $this->response->setHeader('X-PHP-Response-Code', 403);
            $this->response->setStatusCode("403");
            $this->response->setResponseStatus(\Library\System\Response\Manager::RESPONSE_STATUS_SUCCESS_FALSE);
            $this->response->setData("Unauthorized access");
            $this->response->send();
        }

        $this->session->setId($_COOKIE['cloudscraper']);
        $this->session->start();
        $this->accountId = $this->session->get('SESSION_PK_ACCOUNT_MASTER');
        $this->userId = $this->session->get('SESSION_PK_USER_MASTER');

        if (empty($this->accountId)) {
            $this->response->setHeader('X-PHP-Response-Code', 403);
            $this->response->setStatusCode("403");
            $this->response->setResponseStatus(\Library\System\Response\Manager::RESPONSE_STATUS_SUCCESS_FALSE);
            $this->response->setData("Unauthorized access");
            $this->response->send();
        }
    }

    /**
     * Register default assets listed in the config using phalcon assets manager
     *
     * @param string $themeType
     */
    public function loadDefaultAssets()
    {
        $assetSettings = $this->getDI()->get("config")->application->themes->default->assets;

        $javaScriptBasePath = $assetSettings->js->basepath;
        $javaScriptSources = $assetSettings->js->source;

        $styleBasePath = $assetSettings->css->basepath;
        $styleSources = $assetSettings->css->source;

        if (count($javaScriptSources) > 0) {
            foreach ($javaScriptSources as $jScript) {
                $this->assets->addJs($javaScriptBasePath . $jScript);
            }
        }

        if (count($styleSources) > 0) {
            foreach ($styleSources as $style) {
                $this->assets->addCss($styleBasePath . $style);
            }
        }
    }
}