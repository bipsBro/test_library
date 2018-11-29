<?php
namespace Library\Cloudscraper\Acl\SystemRole;
class Manager extends \Library\System\Manager\AbstractManager
{
    /**
     * Adds system role if it doesnt exist
     * @param Array $systemRoleData
     */
    public function addSystemRole(Array $systemRoleData){
        if(count($systemRoleData) > 0){
            #Validate if $systemRoleData has required key to perform further operation.
            $validationManager = new \Library\System\Validation\Manager();
            $validationManager->validateIfArrayKeyExists([
                'system_role',
                'default',
            ], $systemRoleData);
            
            # Creating new object of system role model.
            $systemRoleModel = new \Library\Cloudscraper\Acl\SystemRole\Models\SystemRole();
            $systemRoleModel->role = $systemRoleData['system_role'];
            $systemRoleModel->default = $systemRoleData['default'];
            
            $responseModel = \Library\Cloudscraper\Acl\SystemRole\Models\SystemRole::findFirst([
                "conditions" => "slug =:slug: and default = :default:",
                "bind" => [
                    "slug" => $systemRoleModel->getSlug(),
                    "default"=>$systemRoleData['default']
                ]
            ]);
            
            if($responseModel instanceof \Library\Cloudscraper\Acl\SystemRole\Models\SystemRole){
                return $responseModel;
            }
            
            $systemRoleModel->save();
            return $systemRoleModel;
        }
    }
    
    /*
     * Returns system roles.
     */
    public function getSystemRoles(Array $criteria){
        if(count($criteria) > 0){
            $validationManager = new \Library\System\Validation\Manager();
            $validationManager->validateIfArrayKeyExists(["conditions"], $criteria);
        }
        
        $systemRoleModels = \Library\Cloudscraper\Acl\SystemRole\Models\SystemRole::find([
            $criteria
        ]);
      
        return $systemRoleModels;
    }
}
?>