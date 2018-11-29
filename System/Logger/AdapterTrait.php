<?php
namespace Library\System\Logger;
Trait AdapterTrait
{
    public function getTransactions(){
        return $this->_queue;
    }
    
    public function getFormattedTransactions()
    {
        $message = "%s: %s\n";
        $formattedTransactions = "=================================== TRANSACTION LOG ========================================= \n";
        
        foreach($this->_queue as $queue)
        {
            $formattedTransactions .= vsprintf($message, array(
                date('d-m-Y H:i:s',$queue->getTime()),
                $queue->getMessage()
            ));
        }
        
        $formattedTransactions .= "=================================== EOF TRANSACTION LOG ========================================= \n";
        return $formattedTransactions;
    }
}