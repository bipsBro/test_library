<?php
namespace Library\System\Event;

class Manager extends \Phalcon\Events\Manager
{
    /**
     * Checks if the eventType passed in the params , is actually defined in the event object.
     * {@inheritDoc}
     * @see \Phalcon\Events\Manager::attach()
     */
    public function attach($eventType, $handler, $priority = 100)
    {
//         if(preg_match("/\:/", $eventType))
//         {
//             $eventTypeList = explode(":", $eventType);
//             $eventNamespace = array_shift($eventTypeList);
//             $eventMethod = array_shift($eventTypeList);
            
//             $event = new \ReflectionClass($eventNamespace);
//             $event = $event->newInstance();
            
//             if (! method_exists($event, $eventMethod)) {
//                 throw new \Library\Event\EventException("Invalid event type $eventType. Method $eventMethod doesnt exist.");
//             }
//         }
        
        parent::attach($eventType, $handler);
        return $this;
    }
    
    /**
     * Detach the event handler from event type baed on the object namespace , 
     * rather than the object signature.
     * 
     * @param unknown $eventType
     * @param unknown $handler
     * @return \Library\Event\Manager
     */
    public function detachByNamespace($eventType, $handler)
    {
        $events = $this->_events;
        $eventHandlers = $events[$eventType];
        
        if(count($eventHandlers) == 0)
        {
            return $this ;
        }
        
        foreach ($eventHandlers as $index => $eventHandler) {
            if ($handler == get_class($eventHandler));
            {
                $handler = $eventHandlers[$index];
                return $this->detach($eventType, $handler);
                break;
            }
        }
        
        return $this;
    }
}

class EventManagerException extends \Phalcon\Exception{}