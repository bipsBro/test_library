<?php
namespace Library\Cloudscraper\Deal;

class Manager extends \Library\System\Manager\AbstractManager
{
    /**
     * Primary key of the deal.
     * @var Integer
     */
    protected $_dealId = 0;
    
    /**
     * Sets deal id
     * @param unknown $dealId
     * @return \Library\Cloudscraper\Dataroom\Manager
     */
    public function setDealId($dealId){
        $this->_dealId = $dealId;
        return $this;
    }
    
    /**
     * Returns deal id.
     * @return number
     */
    public function getDealId(){
        return $this->_dealId;
    }
    
    /**
     * Returns users that has access to a deal.
     * @required dealId.
     * @return array|boolean
     */
    public function getDealAccessors(){
        $validationManager = new \Library\System\Validation\Manager();
        
        $validationManager->add(
            'dealId',
            new \Phalcon\Validation\Validator\PresenceOf()
        );
        
        $validationManager->throwExceptionIfInvalid(['dealId'=>$this->getDealId()],"\Library\Cloudscraper\Deal\ManagerException");
        
        $accessors = $bindParams = [];
        
        # Get deal accesor based on the information recorded in the  DealLead table.
        $criteria = \Library\Cloudscraper\Deal\Models\DealLead::query();
        $criteria->columns([
           "UM.FIRST_NAME as firstName",
           "UM.LAST_NAME as lastName",
           "UM.PK_USER_MASTER as userId",
            "UM.EMAIL_ID as emailId",
            "TRL.TEAM_ROLES as role",
            "\Library\Cloudscraper\Deal\Models\DealLead.PK_DEAL_MASTER as dealId" 
        ]);
        $criteria->join("\Library\Cloudscraper\User\Models\UserMaster",
                "UM.PK_USER_MASTER = \Library\Cloudscraper\Deal\Models\DealLead.DEAL_LEAD_PK_USER_MASTER","UM");
        $criteria->join("\Library\Cloudscraper\Acl\TeamRole\Models\TeamRole",
                "TRL.PK_TEAM_ROLES =\Library\Cloudscraper\Deal\Models\DealLead.PK_TEAM_ROLES ","TRL");
        
        $criteria->where("\Library\Cloudscraper\Deal\Models\DealLead.PK_DEAL_MASTER = :dealId:");
        $criteria->bind(["dealId" => $this->getDealId()]);
        
        $results = $criteria->execute();
       
        if(count($results) > 0){
            $results = \Library\System\Helpers\ArrayHelper::groupArrayByPrimaryKey($results->toArray(),"userId");
            $accessors = array_replace($accessors,$results);
        }
        
        # Get deal accessor based on the information recorded in the DealAccess table.
        $criteria = \Library\Cloudscraper\Deal\Models\DealAccess::query();
        $criteria->columns([
            "\Library\Cloudscraper\Deal\Models\DealAccess.DEAL_ACCESS_TO_USER as userId",
            "UM.EMAIL_ID as emailId",
            "UM.FIRST_NAME as firstName",
            "UM.LAST_NAME as lastName",
            "AT.ROLE as role",
            "\Library\Cloudscraper\Deal\Models\DealAccess.PK_DEAL_MASTER as dealId" 
        ]);
        
        $criteria->join("\Library\Cloudscraper\User\Models\UserMaster",
                    "UM.PK_USER_MASTER = \Library\Cloudscraper\Deal\Models\DealAccess.DEAL_ACCESS_TO_USER","UM");
        $criteria->join("\Library\Cloudscraper\Account\Models\AccountMaster",
                    "AM.PK_ACCOUNT_MASTER = \Library\Cloudscraper\Deal\Models\DealAccess.DEAL_ACCESS_TO_USER","AM");
        $criteria->join("\Library\Cloudscraper\Account\Models\AccountType",
                    "AT.PK_ACCOUNT_TYPES = AM.PK_ACCOUNT_TYPES","AT");
        
        $criteria->andWhere("\Library\Cloudscraper\Deal\Models\DealAccess.DEAL_ACCESS = :dealAccessType:");
        $bindParams['dealAccessType'] = \Library\Cloudscraper\Deal\Models\DealAccess::DEAL_ACCESS_GRANTED;
        
        $criteria->andWhere("\Library\Cloudscraper\Deal\Models\DealAccess.PK_DEAL_MASTER = :dealId:");
        
        $bindParams["dealId"] = $this->getDealId();
        
        if(count($bindParams) > 0){
            $criteria->bind($bindParams);
        }
       
        $results = $criteria->execute();
       
        if(count($results) > 0){
            $results = \Library\System\Helpers\ArrayHelper::groupArrayByPrimaryKey($results->toArray(),"userId");
            $accessors = array_replace($accessors,$results);
        }
        
        if(count($accessors) > 0){
            return $accessors;
        }
        
        return false;
    }
}

class ManagerException extends \Phalcon\Exception{
    
}