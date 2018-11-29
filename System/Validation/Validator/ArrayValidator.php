<?php
namespace Library\System\Validation\Validator;

use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;

class ArrayValidator extends Validator implements \Phalcon\Validation\ValidatorInterface
{

    protected $_itemType;
    
    protected $_validationFilter;
    
    protected $_validationFilterFlag;
    
    /**
     * Executes the validation
     *
     * @param Phalcon\Validation $validator            
     * @param string $attribute            
     * @return boolean
     */
    public function validate(Validation $validator, $attribute)
    {
        $items = $validator->getValue($attribute);
        
        $validationResult = true;
        if(count($items) == 0 || !is_array($items)){
            
            $message = $this->getOption("message");
            
            if (! $message) {
                $message = "The Array is not valid";
            }
            
            $validator->appendMessage(new Message($message, $attribute, "Array"));
            
            return false;
        }
        
        foreach($items as $item){
            if (! filter_var($item, $this->_validationFilter, $this->_validationFilterFlag)) {
                $message = $this->getOption("message");
                
                if (! $message) {
                    $message = "The Array is not valid";
                }
                
                $validator->appendMessage(new Message($message, $attribute, "Array"));
                $validationResult = false;
                break;
            }
        }
        
        return $validationResult;
    }
    
    /**
     * Sets validation type for each array item.
     * @param string $itemType
     */
    public function setItemValidationType($itemType="integer"){
        switch($itemType){
            case "integer" :
                $this->_validationFilter = FILTER_VALIDATE_INT;
                $this->_validationFilterFlag = FILTER_FLAG_ALLOW_OCTAL | FILTER_FLAG_ALLOW_HEX;
                break;
        }
    }
}