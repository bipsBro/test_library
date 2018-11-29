<?php
namespace Library\System\Manager;

class AbstractManager extends \Phalcon\Di\Injectable
{
    /**
     */
    protected $_serviceManager = NULL;
    
    use \Library\System\Service\ManagerTrait;
    
    /**
     * Data access object, might be model or manager.
     *
     * @var \Library\Elasticsearch\Manager
     */
    protected $_dataAccessObject = NULL;

    /**
     * logger
     *
     * @var Array Object
     */
    protected $_logger = NULL;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->_dataAccessObject = new \ArrayObject();
    }


    /**
     * Returns logger.
     * 
     * @return \Library\System\Manager\Array
     */
    public function getLogger()
    {
        if ($this->_logger == NULL) {
            $this->_logger = $this->getDI()->get("logger");
        }
        
        return $this->_logger;
    }
    
}