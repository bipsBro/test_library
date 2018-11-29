<?php
namespace Library\System\Model;

class AbstractModel extends \Phalcon\Mvc\Model
{

    /**
     * @struct:
     * md5()
     * 
     * @var array
     */
    protected $_eventListeners = [
        'beforeSave' => [],
        'afterSave' => [],
        'afterDelete'=>[],
        'afterUpdate'=>[]
    ];

    /**
     * Sets event listener to be attached to the model.
     * 
     * @param unknown $class            
     * @param unknown $method            
     * @return \Library\System\Model\AbstractModel
     */
    public function setEventListener($eventType, $object, $method, $params = [])
    {
        if(!array_key_exists($eventType, $this->_eventListeners)){
           throw new \Library\System\Model\AbstractModelException("Unregistered event type $eventType");     
        }
        
        if(!is_object($object)){
            throw new \Library\System\Model\AbstractModelException("$object is not an object");
        }
        
        if(!method_exists($object, $method)){
            throw new \Library\System\Model\AbstractModelException(sprintf("$method is not defined in %s",get_class($object)));
        }
        
        $this->_eventListeners[$eventType][] = [
            "object" => $object,
            "method" => $method,
            "params" => $params
        ];
        
        return $this;
    }
    
    /**
     * Attch event listener to model events.
     */
    public function attachEventListeners(){
        $eventsManager = new \Phalcon\Events\Manager();
        $eventListeners = array_filter(array_values($this->_eventListeners));
       
        if(!empty($eventListeners)){
            foreach($this->_eventListeners as $eventType => $eventListeners){
                if(empty($eventListeners)){
                    continue;    
                }
                
                $eventsManager->attach("model:$eventType", function (\Phalcon\Events\Event $event, $data) use($eventListeners) {
                    foreach($eventListeners as $eventListener){
                        call_user_func_array([ $eventListener['object'],$eventListener['method']], [$data]);
                    }
                });
            }
        }
         
        // Attach the events manager to the event
        $this->setEventsManager($eventsManager);
    }
    
    public function initialize()
    { 
        $modelEventsListeners = $this->getDI()->get("config")->modelEventsListeners;
        // If eventlisteners is not configured then no need to attach the listeners.
        if(count($modelEventsListeners) == 0){
            try{
                $validModelEventsListeners = \Library\System\Helpers\ArrayHelper::isArrayKeysValid(
                    $modelEventsListeners->toArray(), [
                    $this->getSource(),"events","0","listeners","0"
                ]);
               
               
                if(!$validModelEventsListeners){
                    return;
                }
               
                foreach($modelEventsListeners[$this->getSource()]->events as $modelEventListeners){
                    $eventType= $modelEventListeners->event;
                    foreach($modelEventListeners->listeners->toArray() as $listener){
                        $object= array_shift($listener);
                        $method = array_shift($listener);
                        if(!is_object($object)){
                           $object = new $object();
                        }
                        
                        $this->_eventListeners[$eventType][] = [
                            "object" => $object,
                            "method" => $method
                        ];
                    }
                }
                
                
                $this->attachEventListeners();
            }catch(\Library\System\Helpers\ArrayHelperException $exception){}
        }
    }
    
    /**
     * Returns event listeners waiting to be attached
     */
    public function getEventListeners()
    {
        return $this->_eventListeners;
    }
    
    /**
     * Returns formatted messages.
     * 
     * @param string $format
     * @return string
     */
    public function getFormattedMessages($format="<br />"){
        $messages = $this->getMessages();
        $formattedMessages = [];
        if(count($messages) >0){
            foreach($messages as $message){
                array_push($formattedMessages, (string)$message);
            }
           
            if(empty($format)){
                return $formattedMessages;
            }
            
            return implode($format,$formattedMessages);
        }
    }
    
    /**
     * Sets model properties based on the key value provided in data array.
     * @param \Phalcon\Mvc\Model $model
     * @param array $data
     * @return \Phalcon\Mvc\Model
     */
    public function setModelProperties(\Phalcon\Mvc\Model $model,Array $data){
        if(count($data) == 0){
            return $model;
        }
        
        foreach($data as $property => $value){
            if(property_exists($model, $property)){
                $model->$property = $value;
            }
        }
        
        return $model;
    }
    
    
    
}

class AbstractModelException extends \Phalcon\Exception{}