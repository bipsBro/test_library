<?php
namespace Library\System\Event;

trait EventTrait
{

    protected $_eventsManager;

    public function setEventsManager(\Phalcon\Events\ManagerInterface $eventsManager)
    {
        $this->_eventsManager = $eventsManager;
        return $this;
    }

    public function getEventsManager()
    {
        if (! $this->_eventsManager instanceof \Phalcon\Events\ManagerInterface) {
            throw new \Library\Event\EventException("Invalid events manager.");
        }
        return $this->_eventsManager;
    }
    
    
}