<?php 
namespace Library\Cloudscraper\Acl\TransactionRole\Helpers;
class TransactionRole {
    
    
    /*
     * Returns transaction roles.
     */
    public function getTransactionRoles(){
        $transactionRoleManager = new \Library\Cloudscraper\Acl\TransactionRole\Manager();
        $transactionRoles = $transactionRoleManager->getTransactionRoles([]);
        return $transactionRoles;
    }
    
    /**
     * Return transaction roles as HTML Select elements.
     */
    public function getTransactionRolesAsDropList($data=[],$name,$attributes=['class'=>"ui dropdown"]){
        $transactionRoleOptions = [];
        if(count($data) == 0){
            $data = $this->getTransactionRoles();
            foreach ($data as $datum) {
                $transactionRoleOptions[$datum->id] = $datum->transaction_role;
            }
            
            $data = $transactionRoleOptions;
        }
        
        $htmlSelectElement =  new \Phalcon\Forms\Element\Select($name, $data,$attributes);
        $htmlSelectElement->addOption([""=>"select"]);
        $htmlSelectElement->setDefault("");
        return $htmlSelectElement;
        
    }
}
?>