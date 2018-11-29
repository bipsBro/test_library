<?php
namespace Library\System\Application;

class Cli extends \Phalcon\Cli\Console\Extended
{

    protected $_arguments = [];

    protected $di;

    public function setDI(\Phalcon\DiInterface $di = NULL)
    {
        if (! $di instanceof \Phalcon\DiInterface) {
            $di = new \Phalcon\Di\FactoryDefault\Cli();
        }
        
        parent::setDI($di);
        
        return $this;
    }

    public function setConfig($config)
    {
        $this->getDI()->set('config', function () use ($config) {
            return $config;
        }, true);
        
        return $this;
    }

//     public function setLoader()
//     {
//         $loader = new \Phalcon\Loader();
//         // Get all the modules registered in config
//         $modules = $this->getDI()->get('config')->modules;
//         $tasksDirs = Array();
//         if (count($modules) > 0) {
//             foreach ($modules as $moduleName => $module) {
//                 if (empty($module->tasksDir)) {
//                     continue;
//                 }
                
//                 array_push($tasksDirs, $module->tasksDir);
//             }
//         }
        
//         $loader->registerDirs($tasksDirs);
       
//         /**
//          * @todo: load modules and register namespaces.
//          */
//         $loader->registerNamespaces(array(
//             "Application\\Modules\\User\\Tasks" => APPLICATION_BASE_PATH . "/modules/user/tasks/"
//         ));
        
//         $this->registerModules(array(
//             'user' => array(
//                 "className" => "Application\Modules\User\Module",
//                 "path" => APPLICATION_BASE_PATH."/modules/user/Module.php",
//             )
//         ));
        
//         $loader->register();
//         return $this;
//     }
   

    /**
     * Sets arguments supplied in CLI task.
     *
     * @throws \Phalcon\Cli\Console\Exception
     */
    public function setArguments($args)
    {
        if (count($args) == 0) {
            throw new \Phalcon\Cli\Console\Exception("Not enough argument to proceed further.");
        }
        
        $this->_arguments["params"] = [];
        
        foreach ($args as $k => $arg) {
            if ($k === 1) {
                $this->_arguments["module"] = $arg;
            }
            elseif ($k === 2) {
                $this->_arguments["task"] = $arg;
            }
            elseif ($k === 3) {
                $this->_arguments["action"] = $arg;
            } elseif ($k >= 4) {
                if(preg_match_all("/\=/",$arg)){
                    $formattedArgs = explode("=",$arg);
                    $this->_arguments["params"][array_shift($formattedArgs)] = array_shift($formattedArgs);
                }else{
                    $this->_arguments["params"][] = $arg;
                }
            }
        }
        
        return $this;
    }

    /**
     * Returns arguments .
     */
    public function getArguments()
    {
        if (count($this->_arguments) == 0) {
            $this->setArguments($_SERVER['argv']);
        }
        
        return $this->_arguments;
    }

    /**
     * Loads Console Application
     */
    public function start()
    {
        $di = $this->getDI();
        
        
        $args = $this->getArguments();
        if(count($args) == 0)
        {
            return;
        }
        
        try {
            $this->handle($this->getArguments());
        } catch (\Phalcon\Exception $e) {
            echo $e->getMessage();
            exit(255);
        }
        
        return $this;
    }
}
?>