<?php 
namespace Library\Cloudscraper\Acl\Group\Helpers;
class Group {
    
    /*
     * Returns transaction roles.
     */
    public function getTransactionPositions(){
        $aclGroupManager = new \Library\Cloudscraper\Acl\Group\Manager();
        $transactionPositions = $aclGroupManager->getTransactionPositions();
        return $transactionPositions;
    }
    
    /**
     * Returns system roles as HTML Select elements.
     */
    public function getTransactionPositionsAsDropList($name){
        $transactionPositions = $this->getTransactionPositions();
        if(count($transactionPositions) > 0){
            $transactionPositions = array_combine(array_values($transactionPositions), $transactionPositions);
            
            $htmlSelectElement = new \Phalcon\Forms\Element\Select($name, $transactionPositions);
            $htmlSelectElement->addOption([""=>"select"]);
            $htmlSelectElement->setDefault("");
            return $htmlSelectElement;
        }
        
    }
}
?>