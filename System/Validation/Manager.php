<?php
namespace Library\System\Validation;

class Manager extends \Phalcon\Validation
{

    private $__messages = [];

    const MESSAGE_DEFAULT_PRESENCE_OF = "Required field %s is missing";

    /**
     * Custom validation
     *
     * {@inheritdoc}
     *
     * @see \Phalcon\Validation::validate()
     */
    public function validate($data = NULL, $entity = NULL)
    {
        $messages = parent::validate($data, $entity);
       
        if (count($messages) > 0) {
           
            foreach ($messages as $message) {
                array_push($this->__messages, (string) $message);
            }
            
            return false;
        }
        
        return true;
    }

    /*
     * Returns message in an array rather than the big object.
     */
    public function getFormattedMessages($format = '')
    {
        if (empty($format)) {
            return $this->__messages;
        }
        
        return implode($format, $this->__messages);
    }

    public function throwExceptionIfInvalid($data, $exceptionClassNamespace, $statusCode = null )
    {
        $validationResult = $this->validate($data);
        if (! $validationResult) {
            $exception = new \ReflectionClass($exceptionClassNamespace);
            throw $exception->newInstance(json_encode($this->getFormattedMessages()), $statusCode );
        }
        
        return $validationResult;
    }
    
    public function validateIfArrayKeyExists($validationKeys,$data){
        
        foreach($validationKeys as $validationKey){
            $this->add($validationKey, new \Phalcon\Validation\Validator\PresenceOf([
                'message' => sprintf(self::MESSAGE_DEFAULT_PRESENCE_OF,$validationKey)
            ]));
        }
       
        return $this->validate($data);
    }
}