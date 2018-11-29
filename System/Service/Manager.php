<?php
namespace Library\System\Service;

class Manager extends \Phalcon\Di\Injectable
{
    /**
     * Services
     * @var array
     */
    protected $_services = Array();
    
    /**
     * Validator
     * 
     * @var array
     */
    protected $_validator = NULL;
    
    /**
     * Sets service
     * 
     * @param string $serviceName            
     * @param Object $service            
     */
    public function setService($serviceName, $service)
    {
        $validator = new \Library\System\Validation\Manager();
        $validator->add("serviceName", new \Phalcon\Validation\Validator\PresenceOf(["message" => "The name is required"]));
        $validator->add("service", new \Phalcon\Validation\Validator\PresenceOf(["message" => "The name is required"]));
        
        $data = [
            "serviceName" => $serviceName,
            "service" => $service
        ];
        
        $validator->throwExceptionIfInvalid($data, "\Library\System\Service\ServiceManagerException");
       
        $this->_services[$serviceName] = $service;
        return $this;
    }
    
    /**
     * Creates class from namespace saved and initializes the constructor.
     * @param unknown $serviceName
     * @param string $constructorParams
     * @throws \Library\System\Service\ServiceManagerException
     * @return unknown
     */
    public function getService($serviceName,$constructorParams=[])
    {
        if (array_key_exists($serviceName, $this->_services)) {
            $serviceNamespace = $this->_services[$serviceName];
            if(!is_object($serviceNamespace)){
                $serviceClass = new \ReflectionClass($serviceNamespace);
                $serviceClass = $serviceClass->newInstanceArgs($constructorParams);
                return $serviceClass;
            }
            return $serviceNamespace;
        }
        
        throw new \Library\System\Service\ServiceManagerException("Cannot locate service $serviceName ");
    }
    
    /**
     * Sets services
     *
     * @param Array $services
     *            @format $services= [['serviceName'=>$serviceName,'service'=>$serviceObject]]
     */
    public function setServices(Array $services)
    {
        if (count($services) > 0) {
            foreach ($services as $service) {
                $this->setService($service['serviceName'], $service['service']);
            }
        }
        return $this;
    }
    
}

class ServiceManagerException extends \Phalcon\Exception
{
}