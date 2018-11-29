<?php
namespace Library\System\Logger;

class Adapter extends \Phalcon\Di\Injectable
{

    /**
     * Returns object of \Phalcon\Logger\Adapter\File
     * 
     * @param unknown $config            
     */
    public function factory()
    {
        $loggerConfig = $this->getDI()->get('config')->logger;
        $class = new \ReflectionClass($loggerConfig->adapter);
        $logger = $class->newInstanceWithoutConstructor();
        if (method_exists($logger, 'factory')) {
            return $logger->factory($loggerConfig);
        }
        return $logger;
    }
}