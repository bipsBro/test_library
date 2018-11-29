<?php
namespace Library\System\Logger\Adapter;

class Stream extends \Phalcon\Logger\Adapter\Stream implements \Library\System\Logger\AdapterInterface
{
    use \Library\System\Logger\AdapterTrait;
    /**
     * Returns object of \Phalcon\Logger\Adapter\File
     * @param unknown $config
     */
    public static function factory(\Phalcon\Config $config)
    {
        $logger = new self($config->streamType);
        $logger->setLogLevel($config->logLevel);
        return $logger;
    }
}