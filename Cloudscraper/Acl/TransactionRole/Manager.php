<?php
namespace Library\Cloudscraper\Acl\TransactionRole;
class Manager extends \Library\System\Manager\AbstractManager
{
    /**
     * Adds transaction role if it doesnt exist
     * @param Array $transactionRoleData
     */
    public function addTransactionRole(Array $transactionRoleData){
        if(count($transactionRoleData) > 0){
            $transactionRoleModel = new \Library\Cloudscraper\Acl\TransactionRole\Models\TransactionRole();
            $transactionRoleModel->transaction_role = $transactionRoleData['transaction_role'];
            
            $responseModel = \Library\Cloudscraper\Acl\TransactionRole\Models\TransactionRole::findFirst([
                "conditions" => "slug =:slug:",
                "bind" => [
                    "slug" => $transactionRoleModel->getSlug()
                ]
            ]);
            
            if($responseModel instanceof \Library\Cloudscraper\Acl\TransactionRole\Models\TransactionRole){
                return $responseModel;
            }
            
            $transactionRoleModel->save();
            
            return $transactionRoleModel;
        }
    }
    
    
    public function getTransactionRoles(Array $criteria){
        if(count($criteria) > 0){
            $validationManager = new \Library\System\Validation\Manager();
            $validationManager->validateIfArrayKeyExists(["conditions"], $criteria);
        }
        
        $transactionRoleModels = \Library\Cloudscraper\Acl\TransactionRole\Models\TransactionRole::find([
            $criteria
        ]);
       
        
        return $transactionRoleModels;
    }
}
?>