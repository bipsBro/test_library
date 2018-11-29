<?php
namespace Library\System\Application;

/**
 *
 * @author dkarki
 *        
 */
class Manager extends \Phalcon\Mvc\Application
{

    /**
     *
     * @var string Base Path
     */
    protected $_applicationBasePath;

    /**
     *
     * @var Project root path.
     */
    protected $_projectRootPath;

    /**
     * Sets appplication environment.
     *
     * @param String $applicationEnv
     * @return \Library\System\Application\Manager
     */
    public function setApplicationEnvironment($applicationEnv)
    {
        $this->getDI()->set("applicationEnvironment", function () use ($applicationEnv) {
            return $applicationEnv;
        }, true);
        
        return $this;
    }

    /**
     *
     * @param unknown $applicationBasePath
     * @return \Library\Application\Manager
     */
    public function setApplicationBasePath($applicationBasePath)
    {
        $this->_applicationBasePath = $applicationBasePath;
        return $this;
    }

    /**
     *
     * @return unknown
     */
    public function getApplicationBasePath()
    {
        if (empty($this->_applicationBasePath)) {
            throw new \Phalcon\Exception("Application base path undefined.");
        }
        
        return $this->_applicationBasePath;
    }

    /**
     * Sets project root path.
     *
     * @param unknown $projectRootPath
     * @return \Library\Application\Manager
     */
    public function setProjectRootPath($projectRootPath)
    {
        $this->_projectRootPath = $projectRootPath;
        return $this;
    }

    /**
     * Returns project root path.
     *
     * @return \Library\Project
     */
    public function getProjectRootPath()
    {
        if (empty($this->_projectRootPath)) {
            throw new \Phalcon\Exception("Project root path undefined.");
        }
        
        return $this->_projectRootPath;
    }

    /**
     * Resgisters Modules
     *
     * {@inheritdoc}
     *
     * @param Array $modules
     * @param
     *            Array | NULL $merge
     *            
     * @see \Phalcon\Mvc\Application::registerModules()
     */
    public function registerModules(Array $modules, $merge = NULL)
    {
        $namespaces = (array) $this->getDI()
            ->get("loader")
            ->getNamespaces();
        
        if (count($modules) > 0) {
            foreach ($modules as $moduleName => $module) {
                if ($module['default']) {
                    $this->getDI()
                        ->get('router')
                        ->setDefaultModule($moduleName);
                }
                
                $namespaces = array_merge($namespaces, $module['namespace']);
                unset($module['namespace']);
                
                $modules[$moduleName] = $module;
            }
        }
        
        $this->getDI()
            ->get("loader")
            ->registerNamespaces($namespaces)
            ->register();
        
        parent::registerModules($modules);
        return $this;
    }

    /**
     * Sets Dependency injection container.
     *
     * @param
     *            \Phalcon\Di | NULL $dependencyInjector
     * @return \Phalcon\Mvc\Application
     */
    public function initDI($dependencyInjector = NULL)
    {
        if (! $dependencyInjector instanceof \Phalcon\DiInterface) {
            $dependencyInjector = new \Phalcon\Di\FactoryDefault();
        }
        
        $this->setDI($dependencyInjector);
        return $this;
    }

    /**
     * Sets Application Loader
     *
     * @param \Phalcon\Loader\ $loader
     * @return \Phalcon\Mvc\Application
     */
    public function setLoader($loader = NULL)
    {
        if (! $loader instanceof \Phalcon\Loader) {
            $loader = new \Phalcon\Loader();
        }
        
        $this->getDI()->set('loader', $loader);
        return $this;
    }

    /**
     * Get value from Dependency Injection Container.
     *
     * @param String $key
     * @return mixed
     */
    public function get($key = NULL)
    {
        try {
            return $this->getDI()->get($key);
        } catch (\Phalcon\DI\Exception $e) {
            return "";
        }
    }

    /**
     * registers application setting defined in app/config/ as a shared service
     *
     * @param \Phalcon\config $config
     */
    public function setConfig(\Phalcon\config $config)
    {
        $this->getDI()->set('config', function () use ($config) {
            return $config;
        }, true);
        
        return $this;
    }

    /**
     * Registers application routes in DI container as a shared service.
     *
     * @return \Library\Application\Manager
     */
    public function setRoutes($routes)
    {
        if (count($routes) == 0) {
            return $this;
            // throw new \Phalcon\Application\Exception("Invalid routes.");
        }
        
        $router = new \Phalcon\Mvc\Router();
        $router->setDefaultAction("index");
        $router->setDefaultController("deal");
        
        $this->getDI()->set('router', function () use ($router, $routes) {
            foreach ($routes as $route) {
                $routerObj = $router->add($route['pattern'], $route['map'], $route['httpMethods']);
                
                $routerObj->convert('action', function ($action) {
                    if (strstr($action, "-")) {
                        return \Phalcon\Text::camelize($action);
                    }
                    return $action;
                });  
                
                if(isset($route['middlewares'])){
                    $routerObj->match(function() use($route){
                        foreach($route['middlewares'] as $middlewareClass =>$middlewareObj){
                            $instance = new $middlewareClass();
                            $response = $instance->$middlewareObj();
                            if(!empty($response)){
                                return $response;
                            }
                        }
                    }); 
                }
            }
            
            return $router;
        });
        
        return $this;
    }

    /**
     * Sets application view.
     *
     * @param
     *            NULL | \Phalcon\MVC\View $view
     */
    public function setView($view = NULL)
    {
        if (! $view instanceof \Phalcon\MVC\View) {
            $view = new \Phalcon\Mvc\View();
        }
        
        $projectRootPath = $this->getProjectRootPath();
        $viewEngineConfig = $this->getDI()->get('config')->application->viewEngine;
        
        $this->getDi()->set('view', function () use ($view, $projectRootPath, $viewEngineConfig) {
            switch ($viewEngineConfig['engine']) {
                case 'volt':
                    $view->registerEngines(array(
                        "{$viewEngineConfig['extension']}" => function ($view, $di) use ($viewEngineConfig) {
                            $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
                            $volt->setOptions([
                                'compiledPath' => $viewEngineConfig['compiledPath'],
                                'compiledSeparator' => $viewEngineConfig['compiledSeparator'],
                                'compiledExtension' => $viewEngineConfig['compiledExtension'],
                                'compileAlways' => $viewEngineConfig['compileAlways'],
                                'stat' => $viewEngineConfig['stat']
                            ]);
                            
                            if (count($viewEngineConfig['filters'])) {
                                foreach ($viewEngineConfig['filters'] as $filter) {
                                    $volt->getCompiler()
                                        ->addFilter("$filter", function ($param) use ($filter) {
                                        return "$filter(" . $param . ")";
                                    });
                                }
                            }
                            
                            return $volt;
                        }
                    ));
                    break;
                case 'phtml':
                    $view->registerEngines(array(
                        ".phtml" => "Phalcon\Mvc\View\Engine\Php"
                    ));
            }
            
            $view->setViewsDir($projectRootPath);
            return $view;
        }, true);
        return $this;
    }

    /**
     * Sets Namespaces
     *
     * @param array $namespaces
     */
    public function setNamespaces($namespaces)
    {
        if (count($namespaces) == 0) {
            throw new \Phalcon\Application\Exception("No namespaces defined.");
        }
        
        $predefinedNamespaces = (array) $this->getDI()
            ->get("loader")
            ->getNamespaces();
        
        $namespaces = array_merge($predefinedNamespaces, $namespaces);
        
        $this->getDI()
            ->get("loader")
            ->registerNamespaces($namespaces)
            ->register();
        
        return $this;
    }

    public function setHttpClient($client)
    {
        $this->getDI()->set("httpClient", function () use ($client) {
            return $client;
        }, true);
        
        return $this;
    }

    public function setLogger($logger = NULL)
    {
        if ($logger == NULL) {
            $logger = new \Library\System\Logger\Adapter();
            $logger = $logger->factory();
        }
        
        $this->getDI()->set("logger", function () use ($logger) {
            return $logger;
        }, true);
        
        return $this;
    }

    /**
     * Sets event manager.
     *
     * {@inheritdoc}
     *
     * @see \Phalcon\Application::setEventsManager()
     */
    public function loadEventsManager($eventsManager = NULL, $eventsListeners = [])
    {
        if (is_array($eventsManager)) {
            throw new \Library\System\Application\Exception("Events manager should be an object or null.");
        }
        
        if ($eventsManager == NULL) {
            $eventsManager = new \Library\System\Event\Manager();
            $eventListenersObj = Array();
            if (count($eventsListeners) > 0) {
                foreach ($eventsListeners as $eventListeners) {
                    $eventType = $eventListeners->eventType;
                    foreach ($eventListeners->listeners as $eventListenerNamespace) {
                        // If object from stated namespace is not created yet, then create one and
                        // save in the event listeners object list, for future use.
                        if (! array_key_exists($eventListenerNamespace, $eventListenersObj)) {
                            $eventListener = new \ReflectionClass($eventListenerNamespace);
                            $eventListener = $eventListener->newInstance();
                            $eventListenersObj[$eventListenerNamespace] = $eventListener;
                        }
                        
                        $eventsManager->attach($eventType, $eventListenersObj[$eventListenerNamespace]);
                    }
                }
            }
        }
        
        $this->getDI()->set('eventsManager', function () use ($eventsManager) {
            return $eventsManager;
        });
        
        return $this;
    }

    /**
     * Runs application
     *
     * @returns \Phalcon\Http\ResponseInterface
     */
    public function start()
    {
        return $this->handle();
    }

    /**
     * Sets url in dependency injection container.
     */
    public function setUrl(Array $urlConfig)
    {
        $this->getDI()->set("url", function () use ($urlConfig) {
            $url = new \Phalcon\Mvc\Url();
            
            if (array_key_exists("base_uri", $urlConfig)) {
                $url->setBaseUri($urlConfig['base_uri']);
            }
            return $url;
        });
        
        return $this;
    }
    
    /**
     * Set service manager.
     */
    public function setServiceManager()
    {
        $this->getDI()->set("serviceManager", function () {
            return new \Library\System\Service\Manager();
        });
            return $this;
    }
}
