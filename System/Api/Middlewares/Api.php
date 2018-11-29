<?php 
namespace Library\System\Api\Middlewares;
class Api extends \Phalcon\Di\Injectable{

    public function verifyAuthToken(){
        $jwtToken = (string) $this->request->getHeader('Authorization');
        $apiManager = new \Library\System\Api\Manager();

        try{
            $apiManager->authorizeWithJwtToken($jwtToken);

        } catch (\Exception $e) {
            $response = new \Library\System\Response\Manager();
            $response->setHeader('X-PHP-Response-Code', $e->getCode());
            $response->setStatusCode($e->getCode() );
            $response->setMessage($e->getMessage());
            $response->send();
        }
    }
}