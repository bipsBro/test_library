<?php
namespace Library\System\Queue;
class Factory extends \Phalcon\Di\Injectable{
    public function getQueueManager($queueName){
        $amazonQueueConfig = $this->getDi()->get("config")->application->amazon->queue->toArray();
        
        if(!isset($amazonQueueConfig[$queueName])){
            throw new QueueFactoryException("Configuration for queue $queueName is missing in application Config.");
        }
        
        $amazonQueueConfig = $amazonQueueConfig[$queueName];
       
        $queueAdapter = new \Library\System\Queue\Adapter\AmazonSqs();
        $queueAdapter->setRegion($amazonQueueConfig['region']);
        $queueAdapter->setVersion($amazonQueueConfig['version']);
        $queueAdapter->setSqsKey($amazonQueueConfig['credentials']['key']);
        $queueAdapter->setSqsSecret($amazonQueueConfig['credentials']['secret']);
        $queueAdapter->setQueueUrl($amazonQueueConfig['queueUrl']);
        
        $queueAdapter->init();
        
        $queueManager = new \Library\System\Queue\Manager();
        $queueManager->setSqsClient($queueAdapter);
        
        return $queueManager; 
    }
}

class QueueFactoryException extends \Phalcon\Exception{}