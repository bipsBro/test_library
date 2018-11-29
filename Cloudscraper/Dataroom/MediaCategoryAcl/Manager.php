<?php
namespace Library\Cloudscraper\Dataroom\MediaCategoryAcl;
class Manager extends \Library\System\Manager\AbstractManager
{
    protected $_groupId;
    
    protected $_isDefault = true;
    
    protected $_criteria = [];
    /**
     * Adds permission to csx_mediaCategory_acl table.
     */
    public function addPermission(Array $data){
        $validationManager = new \Library\System\Validation\Manager();
        $dataValidationStatus = $validationManager->validateIfArrayKeyExists([
            'media_category_slug',
            'group_id',
            'access_read',
            'access_write',
            'access_manage',
            'access_download',
            'access_delete',
            'created_by',
            'default'
        ], $data);
        
        if(!$dataValidationStatus){
            throw new ManagerException($validationManager->getFormattedMessages('string'));
        }
        
        $mediaCategoryAcl = \Library\Cloudscraper\Dataroom\MediaCategoryAcl\Models\MediacategoryAcl::findFirst([
            'conditions' => "media_category_slug = :media_category_slug:  and group_id = :group_id:",
            "bind" => [
                "media_category_slug" => $data['media_category_slug'],
                "group_id" => $data['group_id']
            ]
        ]);
        
        
        if($mediaCategoryAcl instanceof \Library\Cloudscraper\Dataroom\MediaCategoryAcl\Models\MediacategoryAcl){
            return $mediaCategoryAcl;
        }
        
        $mediaCategoryAcl = new \Library\Cloudscraper\Dataroom\MediaCategoryAcl\Models\MediacategoryAcl();
        $mediaCategoryAcl->media_category_slug = $data['media_category_slug'];
        $mediaCategoryAcl->group_id = $data['group_id'];
        $mediaCategoryAcl->access_read = $data['access_read'];
        $mediaCategoryAcl->access_write = $data['access_write'];
        $mediaCategoryAcl->access_manage = $data['access_manage'];
        $mediaCategoryAcl->access_download = $data['access_download'];
        $mediaCategoryAcl->access_delete = $data['access_delete'];
        $mediaCategoryAcl->created_by = $data['created_by'];
        $mediaCategoryAcl->default = $data['default']; 
        if($mediaCategoryAcl->save() == false){
           
            throw new \Exception($mediaCategoryAcl->getFormattedMessages());
        }
        
        return $mediaCategoryAcl;
    }
    
    /**
     * Set criteria for data filter.
     * 
     * @param unknown $condition
     * @param unknown $bindValues
     * @return \Library\Cloudscraper\Dataroom\MediaCategoryAcl\Manager
     */
    public function setCriteria($condition,Array $bindValues){
        $this->_criteria = [
            "conditions" => $condition,
            "bind" => $bindValues
        ];
        return $this;
    }
    
    /**
     * Rweturns criteria for data filter.
     * @return array|string[]|unknown[]
     */
    public function getCriteria(){
        return $this->_criteria;
    }
    
    /**
     * Return categories with ACL.
     * @param array $criteria
     */
    public function loadCategoriesAcl(){
        $criteria = \Library\Cloudscraper\Dataroom\MediaCategoryAcl\Models\MediacategoryAcl::query();
        
        $criteria->columns([
            "GRP.id as group_id",
            "GRP.transaction_role_id",
            "GRP.system_role_id",
            "MC.name as media_category_name",
            "\Library\Cloudscraper\Dataroom\MediaCategoryAcl\Models\MediacategoryAcl.media_category_slug",
            "\Library\Cloudscraper\Dataroom\MediaCategoryAcl\Models\MediacategoryAcl.access_read",
            "\Library\Cloudscraper\Dataroom\MediaCategoryAcl\Models\MediacategoryAcl.access_write",
            "\Library\Cloudscraper\Dataroom\MediaCategoryAcl\Models\MediacategoryAcl.access_manage",
            "\Library\Cloudscraper\Dataroom\MediaCategoryAcl\Models\MediacategoryAcl.access_delete",
            "\Library\Cloudscraper\Dataroom\MediaCategoryAcl\Models\MediacategoryAcl.access_download",
            "GRP.transaction_position",
            "TR.transaction_role",
            "SR.role"
        ]);
       
        $criteria->leftJoin("\Library\Cloudscraper\Dataroom\MediaCategory\Models\MediaCategory","MC.slug = \Library\Cloudscraper\Dataroom\MediaCategoryAcl\Models\MediacategoryAcl.media_category_slug","MC");
        $criteria->leftJoin("\Library\Cloudscraper\Acl\Group\Models\Group",NULL,"GRP");
        $criteria->leftJoin("\Library\Cloudscraper\Acl\SystemRole\Models\SystemRole","GRP.system_role_id=SR.id","SR");
        $criteria->leftJoin("\Library\Cloudscraper\Acl\TransactionRole\Models\TransactionRole","GRP.transaction_role_id = TR.id","TR");
        
        $criteria->orderBy("MC.id asc");
        //$criteria->groupBy("MC.slug");
        if(count($this->getCriteria()) >0){
            $criteria->where($this->_criteria["conditions"]);
            $criteria->bind($this->_criteria["bind"]);
        }
        
        $result = $criteria->execute();
        return $result;  
    }
}

class ManagerException extends \Phalcon\Exception{
    
}