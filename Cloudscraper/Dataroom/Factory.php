<?php
namespace Library\Cloudscraper\Dataroom;
class Factory extends \Phalcon\Di\Injectable{
    /**
     * Creates object of type \Library\Cloudscraper\Dataroom\Manager
     * @return \Library\Cloudscraper\Dataroom\Manager
     */
    public static  function createDataroomManager(){
        $di = \Phalcon\DI::getDefault(); 
        $amazonConfig = $di->get("config")->application->amazon->s3;
        $storageAdapter = new \Library\Cloudscraper\Dataroom\Storage\Adapter\AmazonS3();
        $storageAdapter->setS3Key($amazonConfig->credentials->key);
        $storageAdapter->setS3StorageRegion($amazonConfig->region);
        $storageAdapter->setS3StorageBucket($amazonConfig->bucket);
        $storageAdapter->setS3Version($amazonConfig->version);
        $storageAdapter->setAmazonSecret($amazonConfig->credentials->secret);
        $storageAdapter->init();
        
        $dataroomManager = new \Library\Cloudscraper\Dataroom\Manager();
        $dataroomManager->setStorageAdapter($storageAdapter);
        return $dataroomManager;
    }
}