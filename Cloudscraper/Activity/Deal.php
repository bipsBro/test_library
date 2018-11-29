<?php
namespace Library\Cloudscraper\Activity;
class Deal extends \Library\System\Manager\AbstractManager
{
    /**
     * Retrieves the message from DealQueue and checks if any new deal is created.
     */
    public function isDealCreated()
    {
        $queueFactory = new \Library\System\Queue\Factory();
        $queueManager = $queueFactory->getQueueManager('dealQueue');
        $messages = $queueManager->getMessage();

        if ($messages) {
            foreach ($messages as $message) {
                // Capture created date, created by, created id and updated date as well.
                $receiptHandle = $message['ReceiptHandle'];
                $message = $message['Body'];
                $message = json_decode($message);
                if($message != false){
                    $this->getDI()
                        ->get("eventsManager")
                        ->fire("\Library\Cloudscraper\Activity\Deal:afterDealCreated",$message);

                   // delete the message from queue.
                   $queueManager->deleteMessage($receiptHandle);
                }
            }
        }
    }
}