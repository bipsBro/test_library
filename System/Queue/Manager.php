<?php
namespace Library\System\Queue;

class Manager extends \Library\System\Manager\AbstractManager
{
    /**
     *
     * @var $_sqsClient;
     */
    protected $_sqsClient;

    /**
     * Sets SQS client.
     *
     * @param unknown $sqsClient
     * @return \Library\System\Queue\Manager
     */
    public function setSqsClient($sqsClient)
    {
        $this->_sqsClient = $sqsClient;
        return $this;
    }

    /**
     * Return SQS client.
     *
     * @return unknown
     */
    public function getSqsClient()
    {
        return $this->_sqsClient;
    }

    /**
     * Get message from queue
     */
    public function getMessage()
    {
        return $this->getSqsClient()->getMessages();
    }
    
    /**
     * Delete message from queue
     * @param string $message
     */
    public function deleteMessage($receiptHandle)
    {
        return $this->getSqsClient()->deleteMessage($receiptHandle);
    }
}