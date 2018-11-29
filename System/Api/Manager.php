<?php
namespace Library\System\Api;

/**
 * Created by PhpStorm.
 * User: bpandey
 * Date: 11/15/18
 * Time: 11:43 AM
 */

use Firebase\JWT\JWT;

class Manager extends \Phalcon\Mvc\Application
{
    /**
     * create new API model
     * @return \Library\System\Api\Models\Api
     * @throws \Phalcon\Mvc\Model\Exception
     * @throws \Phalcon\Security\Exception
     */
    public function createApiKeyAndToken(){
        $randomKeyGenerator = new \Phalcon\Security\Random();
        $phalconSecurityLib = new \Phalcon\Security();
        $apiKey = $randomKeyGenerator->hex(10);
        $apiToken = $randomKeyGenerator->hex(5);
        $apiTokenHash = $phalconSecurityLib->hash($apiToken);

        $apiKeyModel = new \Library\System\Api\Models\Api();
        $apiKeyModel->key = $apiKey;
        $apiKeyModel->token_hash = $apiTokenHash;
        $apiKeyModel->setToken($apiToken);
        $apiKeyModel->expires_at = time() +  $this->getDI()->get('config')->application->jwtExpireTime;
        $apiKeyModel->status = \Library\System\Api\Models\Api::APIKEY_STATUS_REQUIRED_TRUE;


        if (! $apiKeyModel->create()) {
            throw new \Phalcon\Mvc\Model\Exception($apiKeyModel->getMessages("\n"));
        }
        return $apiKeyModel;
    }

    /**
     * generate the json web token if @params are valid else throw exception
     * @param $apiKey
     * @param $apiToken
     * @return string
     * @throws ApiManagerException
     */
    public function generateJwtTokenWithKeyAndToken($apiKey, $apiToken){
        $this->ValidateApiKeyAndToken($apiKey, $apiToken);
        $apiModel = $this->getApiModelByKey($apiKey);

        if(!$apiModel instanceof \Library\System\Api\Models\Api){
            throw new ApiManagerException("Invalid api key.", 422);
        }

        $phalconSecurityLib = new \Phalcon\Security();
        if (! $phalconSecurityLib->checkHash($apiToken, $apiModel->token_hash)){
            throw new ApiManagerException("Invalid api key or api token.", 422);
        }

        return $this->buildJsonWebToken([
            "api_key" => $apiKey
        ]);
    }

    public function authorizeWithJwtToken($jwtToken){
        try {
            $this->decodeJsonWebToken($jwtToken);
        } catch (\Exception $e) {
            throw new ApiManagerException($e->getMessage(), 422);
        }
    }
    /**
     * @param $apiKey
     * @param $apiToken
     */
    private function ValidateApiKeyAndToken($apiKey, $apiToken){
        $apiKeyValidator = new \Library\System\Validation\Manager();
        $apiKeyValidator->add("apiKey", new \Phalcon\Validation\Validator\PresenceOf([
            "message" => "The api key is required"
        ]));

        $apiKeyValidator->add("apiToken", new \Phalcon\Validation\Validator\PresenceOf([
            "message" => "The api token is required"
        ]));

        $data = Array(
            "apiKey" => $apiKey,
            "apiToken" => $apiToken
        );

        $apiKeyValidator->throwExceptionIfInvalid($data, "\Library\System\Api\ApiManagerException", 422);
    }

    /**
     * return the Api models with column value $apiKey
     * @param $apiKey
     * @return \Library\System\Api\Models\Api
     */
    private function getApiModelByKey($apiKey){
        $apiModel = \Library\System\Api\Models\Api::findFirst([
            "conditions" => "key = ?1",
            "bind" => [ 1 =>$apiKey ]
        ]);
        return $apiModel;
    }

    /**
     * @param array $data
     * @return string
     */
    private function buildJsonWebToken($data = []){
        $expirationTime = $this->getJwtExpireTime();
        $issuedAt = time();

        $token = [
            "data" => $data,
            "exp" => $expirationTime,
            "iat" => $issuedAt,
        ];
        return $this->generateJsonWebToken($token);
    }

    /**
     * @param $jwtToken
     * @return object
     * @throws ApiManagerException
     */
    private function decodeJsonWebToken($jwtToken){
        $key = $this->getJwtSecretKey();
        try {
            return JWT::decode($jwtToken, $key, array('HS256'));
        } catch (\Exception $e){
            throw new ApiManagerException($e->getMessage(), 422);
        }
    }

    /**
     * @param $token
     * @return string
     */
    private function generateJsonWebToken($token){
        $key = $this->getJwtSecretKey();
        return JWT::encode($token, $key);
    }

    /**
     * @return mixed
     */
    private function getJwtSecretKey(){
        return $this->getDI()->get('config')->application->jwtSecretKey;
    }

    /**
     * @return mixed
     */
    private function getJwtExpireTime(){
        return time() + (int) $this->getDI()->get('config')->application->jwtExpireTime;
    }
}

/**
 * Class ApiManagerException
 * @package Library\System\Api
 */
class ApiManagerException extends \Phalcon\Exception{

}