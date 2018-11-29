<?php
namespace Library\System\Queue\Adapter;

use Aws\Sqs\SqsClient;
use Aws\Exception\AwsException;

class AmazonSqs
{

    /**
     *
     * @var $_sqsClient;
     */
    protected $_sqsClient;

    /**
     *
     * @var $_version;
     */
    protected $_version;

    /**
     *
     * @var $_region;
     */
    protected $_region;

    public function init()
    {
        $this->_sqsClient = new \Aws\Sqs\SqsClient([
            "credentials" => [
                "key" => $this->getSqsKey(),
                "secret" => $this->getSqsSecret()
            ],
            'region' => $this->getRegion(),
            'version' => $this->getVersion()
        ]);
    }

    /**
     *
     * @var $_sqsKey;
     */
    protected $_sqsKey;

    /**
     * Aws credential.
     *
     * @param unknown $sqsKey
     * @return \Library\System\Queue\Adapter\AmazonSqs
     */
    public function setSqsKey($sqsKey)
    {
        $this->_sqsKey = $sqsKey;
        return $this;
    }

    public function getSqsKey()
    {
        return $this->_sqsKey;
    }

    /**
     *
     * @var $_sqsSecret;
     */
    protected $_sqsSecret;

    public function setSqsSecret($sqsSecret)
    {
        $this->_sqsSecret = $sqsSecret;
        return $this;
    }

    public function getSqsSecret()
    {
        return $this->_sqsSecret;
    }

    public function setVersion($version)
    {
        $this->_version = $version;
        return $this;
    }

    public function getVersion()
    {
        return $this->_version;
    }

    public function setRegion($region)
    {
        $this->_region = $region;
        return $this;
    }

    public function getRegion()
    {
        return $this->_region;
    }

    /**
     *
     * @var $_queueUrl;
     */
    protected $_queueUrl;

    public function setQueueUrl($queueUrl)
    {
        $this->_queueUrl = $queueUrl;
        return $this;
    }

    public function getQueueUrl()
    {
        return $this->_queueUrl;
    }
    
    /**
    * 
    *@var  $_totalMessages;
    */
    protected $_totalMessages = 10;
    
    public function setTotalMessages($totalMessages){
        $this->_totalMessages =$totalMessages;
        return $this;
    }
    
    public function getTotalMessages(){
        return $this->_totalMessages ;
    }
    
    /**
     * Get message from queue
     */
    public function getMessages()
    {
        $result = $this->_sqsClient->receiveMessage(array(
            'QueueUrl' => $this->getQueueUrl(),
            'MaxNumberOfMessages' => $this->getTotalMessages()
        ));
        
        $messages = $result->get('Messages');
        
        if(count($messages) > 0){
            return $messages;
        }
        
        return false;
    }
    
    public function deleteMessage($message)
    {
        try {
            $this->_sqsClient->deleteMessage([
                'ReceiptHandle' => $message,
                'QueueUrl' => $this->getQueueUrl()
            ]);
        } catch (AwsException $e) {
            // output error message if fails
            echo "<pre>Error ";
            print_r($e->getMessage());
            echo "</pre>";
            exit;
        }      
    }
    
   
}
