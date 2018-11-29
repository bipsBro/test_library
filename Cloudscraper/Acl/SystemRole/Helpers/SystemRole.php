<?php 
namespace Library\Cloudscraper\Acl\SystemRole\Helpers;
class SystemRole {
    
    /*
     * Returns transaction roles.
     */
    public function getSystemRoles(){
        $systemRoleManager = new \Library\Cloudscraper\Acl\SystemRole\Manager();
        $systemRoles = $systemRoleManager->getSystemRoles([]);
        return $systemRoles;
    }
    
    /**
     * Returns system roles as HTML Select elements.
     */
    public function getSystemRolesAsDropList($name)
    {
        $systemRoles = $this->getSystemRoles();
        if (count($systemRoles) > 0) {
            $systemRoleOptions = [];
            foreach ($systemRoles as $systemRole) {
                $systemRoleOptions[$systemRole->id] = $systemRole->role;
            }
            
            $htmlSelectElement =  new \Phalcon\Forms\Element\Select($name, $systemRoleOptions);
            $htmlSelectElement->addOption([""=>"select"]);
            $htmlSelectElement->setDefault("");
            return $htmlSelectElement;
        }
    }
}
?>