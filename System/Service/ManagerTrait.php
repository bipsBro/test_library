<?php
namespace Library\System\Service;
trait ManagerTrait{
    /**
     */
    protected $_serviceManager = NULL;
    
    /**
     * Sets services
     *
     * @param Array $services
     * @format $services= [['serviceName'=>$serviceName,'service'=>$serviceObject]]
     */
    public function setServices(Array $services)
    {
        if ($this->_serviceManager == NULL) {
            $this->_serviceManager = $this->getDI()->get("serviceManager");
        }
        
        if (count($services) > 0) {
            foreach ($services as $service) {
                $this->_serviceManager->setService($service['serviceName'], $service['service']);
            }
        }
       
        return $this;
    }
    
    public function getService($serviceName, $params = [])
    {
        return $this->_serviceManager->getService($serviceName, $params);
    }
}