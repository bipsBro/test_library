<?php
namespace Library\System\Module;

use PhpParser\Node\Expr\Print_;

Abstract class AbstractModule implements \Phalcon\Mvc\ModuleDefinitionInterface
{

    protected $_module;

    /**
     * Sets module.
     *
     * @param unknown $module            
     * @return AbstractModule
     */
    public function setModule($module)
    {
        $this->_module = $module;
        return $this;
    }

    /**
     * Return module.
     */
    public function getModule()
    {
        return $this->_module;
    }

    public function registerAutoloaders(\Phalcon\DiInterface $dependencyInjector = NULL)
    {
        
    }
    
    public function setModuleDir($moduleDir){
        $this->_moduleDir = $moduleDir;
    }
    
    public function getModuleDir(){
        return $this->_moduleDir;
    }

    public function registerServices(\Phalcon\DiInterface $di)
    {
        $dispatcher = $di->get('dispatcher');
       
        // Get module from config.
        $moduleConfig = $dispatcher->getDi()->get("config")->modules;
        $moduleConfig = $moduleConfig->offsetGet($this->getModule());
        $defaultNamespaces = $moduleConfig->defaultNamespaces;
        // If dispatcher is initiated from web app.
        if ($dispatcher instanceof \Phalcon\Mvc\Dispatcher) {
            if ($defaultNamespaces->offsetExists("webApp")) {
                $dispatcher->setDefaultNamespace($defaultNamespaces->webApp);
               
                $view = $di->get('view');
                $themeSettings = $di->get('config')->application->themes->default;
                $view->setLayoutsDir($themeSettings->layoutsDir);
                $view->setTemplateAfter($themeSettings->defaultLayout);
                
                $view->setViewsDir("{$this->getModuleDir()}/views");
                $di->setShared('view', function () use ($view) {
                    return $view;
                });
               
            }
            
            return $dispatcher;
        }
        
        // If dispatcher is initiated from cli app.
        if ($dispatcher instanceof \Phalcon\Cli\Dispatcher) {
            if ($defaultNamespaces->offsetExists("cliApp")) {
                $dispatcher->setDefaultNamespace($defaultNamespaces->cliApp);
            }
            
            return $dispatcher;
        }
        
        return $this;
    }
}