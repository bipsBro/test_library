<?php
namespace Library\Cloudscraper\Acl\UserGroup;
class Manager extends \Library\System\Manager\AbstractManager
{

    private $userGroupsIds;

    use \Library\Cloudscraper\Dataroom\Traits\Section;
    public function addUserGroup(Array $userGroup){
        
        $validationManager = new \Library\System\Validation\Manager();
        
        $validationManager->validateIfArrayKeyExists([
            'user_id',
            'group_id',
            'section',
            'section_id',
            'created_by'
        ], $userGroup);
        

        $aclUserGroupModel = new \Library\Cloudscraper\Acl\UserGroup\Models\UserGroup();
        $aclUserGroupModel->user_id = $userGroup['user_id'];
        $aclUserGroupModel->group_id = $userGroup['group_id'];
        $aclUserGroupModel->section = $userGroup['section'];
        $aclUserGroupModel->section_id = $userGroup['section_id'];
        $aclUserGroupModel->created_by = $userGroup['created_by'];

        if($aclUserGroupModel->save() == false){
            throw new \Exception($aclUserGroupModel->getFormattedMessages());
        }
        return $aclUserGroupModel;
    }
    


    public function getUserGroups(){
        $userGroupsModel = new \Library\Cloudscraper\Acl\UserGroup\Models\UserGroup();
      
        $userGroups = $userGroupsModel::find([
            "section  = :section:
            and section_id = :section_id:",
            "bind" => [
                "section" => $this->getSection(),
                "section_id" =>$this->getSectionId()
            ]
        ]);
        
        $userGroups = $this->modelsManager->createBuilder()
            ->addFrom("Library\Cloudscraper\Acl\UserGroup\Models\UserGroup","UG")
            ->columns([
                "UG.user_id as userId",
                "UG.group_id as groupId",
                "G.transaction_role_id as transactionRoleId",
                "G.system_role_id as systemRoleId",
                "G.transaction_position as transactionPosition",
                "SR.role as systemRole",
                "TR.transaction_role as transactionRole"
            ])
            ->join("\Library\Cloudscraper\Acl\Group\Models\Group","G.id = UG.group_id","G")
            ->join("\Library\Cloudscraper\Acl\TransactionRole\Models\TransactionRole","TR.id = G.transaction_role_id","TR")
            ->join("\Library\Cloudscraper\Acl\SystemRole\Models\SystemRole","SR.id = G.system_role_id","SR")
            ->where("section = :section: and section_id = :section_id:")
            ->getQuery()
            ->execute([
                "section" => $this->getSection(),
                "section_id" =>$this->getSectionId()
            ]);
            
           
        if(count($userGroups) > 0){
            $userGroups = \Library\System\Helpers\ArrayHelper::groupArrayByPrimaryKey($userGroups->toArray(),"userId");
            return $userGroups;
        }
        
        return [];
    }


    public function setUserGroupIds($userGroupsIds){
        $this->userGroupsIds = $userGroupsIds;
    }

    public function remove(){


        // $idsCondition = array_map(function($id){
        //     return " id = ". $id ." "; 
        // }, $this->userGroupsIds);
        // var_dump($idsCondition);
        // $wherePart = "";

        // if(count($idsCondition) == 1){
        //     $wherePart = $idsCondition[0];
        // }else{
        //     $wherePart = array_reduce($idsCondition, function($accumulator,$iterator){
        //         if($accumulator == null) return $iterator;
        //         return $accumulator . ' or ' . $iterator;
        //     });
        // }

        $ids = $this->userGroupsIds;
        $inQuery = implode(',', array_fill(0, count($ids), '?'));

        $query = "DELETE FROM csx_user_groups WHERE id in(" .$inQuery.')';        
        $statement = $this->getDI()->get('db')->prepare($query);
     
        $result = $statement->execute($ids);
    
        return $result;
    }
}
?>