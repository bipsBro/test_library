<?php
namespace Library\Cloudscraper\Acl\Group;


class Manager extends \Library\System\Manager\AbstractManager
{

    //For single
    private $groupId;
    //For multiple
    private $groupIds = [];
    private $transactionPosition='';
    private $transactionRoleId;
    private $systemRoleId;

    /**
     * Adds group to csx_groups table.
     */
    public function addGroup(Array $group){
        #Validate if group has required key to perform further operation.
        $validationManager = new \Library\System\Validation\Manager();
        $validationManager->validateIfArrayKeyExists([
            'system_role',
            'transaction_role',
            'transaction_position',
            'default',
            'created_by'
        ], $group);
        
        
        #Add or get transaction role. 
        $transactionRoleManager = new \Library\Cloudscraper\Acl\TransactionRole\Manager();
        $transactionRoleModel = $transactionRoleManager->addTransactionRole(["transaction_role" => $group['transaction_role']]);
       
        # Add or get system role.
        $systemRoleManager = new \Library\Cloudscraper\Acl\SystemRole\Manager();
        $systemRoleModel = $systemRoleManager->addSystemRole([
            'system_role' => $group['system_role'],
            'default' => $group['default']
        ]);
        
        #Check if a group is already defined.
        $aclGroupModel = new \Library\Cloudscraper\Acl\Group\Models\Group();
       
        $responseGroupModel = \Library\Cloudscraper\Acl\Group\Models\Group::findFirst([
            'conditions' => "transaction_position = :transaction_position:  and 
                             transaction_role_id = :transaction_role_id: and
                             system_role_id = :system_role_id: and
                             default = :default:",
            "bind" => [
                "transaction_position" => $group['transaction_position'],
                "transaction_role_id" => $transactionRoleModel->id,
                "system_role_id" => $systemRoleModel->id,
                "default" => $group['default']
            ]
        ]);
        
        
        if($responseGroupModel instanceof \Library\Cloudscraper\Acl\Group\Models\Group){
           return $responseGroupModel; 
        }
        
        $aclGroupModel = new \Library\Cloudscraper\Acl\Group\Models\Group();
        
        $aclGroupModel->transaction_position = $group['transaction_position'];
        $aclGroupModel->transaction_role_id = $transactionRoleModel->id;
        $aclGroupModel->system_role_id = $systemRoleModel->id;
        $aclGroupModel->default = $group['default'];
        $aclGroupModel->created_by = $group['created_by'];
        
        if($aclGroupModel->save() == false){
            throw new \Exception($aclGroupModel->getFormattedMessages());
        }
        
        return $aclGroupModel;
    }
    
    public function getTransactionPositions(){
        return \Library\Cloudscraper\Acl\Group\Models\Group::TRANSACTION_POSITIONS;
    }
   
    public function setTransactionPosition($transactionPosition){
        $this->transactionPosition = $transactionPosition;
        return $this;
    }
    
    public function getTransactionPosition(){
        return $this->transactionPosition;
    }

    public function setTransactionRoleId($transactionRoleId){
        $this->transactionRoleId = $transactionRoleId;
        return $this;
    }

    public function setSystemRoleId($systemRoleId){
        $this->systemRoleId = $systemRoleId;
        return $this;
    }

//     public function loadGroupFromFields(){
//         $groupModel = new \Library\Cloudscraper\Acl\Group\Models\Group();
//         //var_dump($this);
//         $group = $groupModel::findFirst([
//             "conditions" => 
//                 "transaction_position = :transaction_position: and
//                  system_role_id = :system_role_id: and
//                  transaction_role_id = :transaction_role_id:",
//             "bind" => [
//                 "transaction_position" => $this->transactionPosition,
//                 "system_role_id" => $this->systemRoleId,
//                 "transaction_role_id" => $this->transactionRoleId
//             ]
//         ]);
//         //return $group;
//         if(!$group){
//             throw new \Exception("Group not found");
//         }
//         return $group;
//     }

    // public function getGroupId(){
    //     return $this->groupId;
    // }


    public function setGroupIds($groupIds){
        $this->groupIds = $groupIds;
        return $this;
    }


    public function getGroups(){
      
        $query = $this->modelsManager->createBuilder()
            ->columns([
                "G.id as groupId",
                "G.transaction_position as transactionPosition",
                "SRL.role as system_role",
                "TRL.transaction_role as transactionRole"
            ])
            ->addFrom("Library\Cloudscraper\Acl\Group\Models\Group","G")
            ->join("\Library\Cloudscraper\Acl\SystemRole\Models\SystemRole","SRL.id = G.system_role_id" , "SRL")
            ->join("\Library\Cloudscraper\Acl\TransactionRole\Models\TransactionRole","TRL.id = G.transaction_role_id" , "TRL");
       
      
        if(count($this->groupIds) >0){
            $query->inWhere("G.id" , $this->groupIds);
        }
        
        if(!empty($this->getTransactionPosition())){
            $query->Where("G.transaction_position = :transaction_position:",['transaction_position'=>$this->getTransactionPosition()]);
        }
       
        
        $groups = $query->getQuery()->execute();
        return $groups->toArray();
    }
}
?>