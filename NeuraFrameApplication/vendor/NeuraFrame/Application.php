<?php

namespace NeuraFrame;

use NeuraFrame\Containers\Container;
use NeuraFrame\Session;
use NeuraFrame\Contracts\Application\ApplicationInterface;


class Application extends Container implements ApplicationInterface
{
    /**
    * Create a new application instance
    *
    * @return void
    */
    public function __construct($basePath = null)
    {
        if($basePath)
            $this->setBasePath($basePath);
        
        static::setInstance($this);              
        $this->registerCoreAliases();   
        $this->loadConfigurations();   
        $this->loadHelpers();
        $this->registerModelAliases();
        $this->registerMiddlewareAliases();   
        Session::start();        
    }

    /**
    * Load configuration files
    *
    * 
    */
    private function loadConfigurations()
    {
        $config['database'] = $this->file->call("config/database.php");
        $config['middleware'] = $this->file->call("config/middleware.php");
        $this->share("config",$config);
    }

    /**
    * Register the core aliases to its array
    *
    * @return void
    */
    private function registerCoreAliases()
    {
        $aliases = [
            'router'                =>  'NeuraFrame\\Routing\\Router',
            'request'               =>  'NeuraFrame\\Http\\Request',
            'twig'                  =>  'NeuraFrame\\View\\TwigRenderer',
            'file'                  =>  'NeuraFrame\\FileSystem\\File',
            'controllerFactory'     =>  'NeuraFrame\\Controller\\ControllerFactory',
            'modelFactory'          =>  'NeuraFrame\\Model\ModelFactory',
            'database'              =>  'NeuraFrame\\Database\\Database',
            'middlewareFactory'     =>  'NeuraFrame\\Middleware\\MiddlewareFactory',
            'validator'             =>  'NeuraFrame\\Validator',
            'password'              =>  'NeuraFrame\\Security\\Password'
        ];

        foreach($aliases as $key => $alias)
        {
            $this->setAlias($key,$alias);
        }
    }

    /**
    * Register all models inside models folder
    *
    * @return void
    */
    private function registerModelAliases()
    {
        $fileNames = $this->file->scanDir($this->file->toApp("Models"));
        foreach($fileNames as $baseName)
        {
            if($this->file->isExtension($baseName,'php'))
            {
                $this->modelFactory->setAlias($this->file->getFileName($baseName));
            }
        }
    }  

    /**
    * Register all middlewares created by user
    *
    * @return void
    */
    private function registerMiddlewareAliases()
    {
        $this->middlewareFactory->registerAliases($this->config['middleware']);
    }

    /**
    * Load Helpers file
    *
    * @return void
    */
    private function loadHelpers()
    {
        $this->file->call('vendor/NeuraFrame/helpers.php');
    }      

    /**
    * Get shared instace from Container 
    * @param string $key
    * @return mixed
    */
    public function __get($key)
    {
        return $this->getSharedInstance($key);
    } 

    /**
    * Get base path of application
    *
    * @return string 
    */
    public function getBasePath()
    {
        return $this->basePath;
    }
}