<?php

namespace NeuraFrame;

use NeuraFrame\Containers\Application\Container;
use NeuraFrame\Contracts\Application\ApplicationInterface;
use App\Routes;

class Application extends Container implements ApplicationInterface
{
    /**
    * Static instance of application
    *
    * @var \NeuraFrame\Application
    */
    static $app;

    /**
    * Base path of NeuraFrame application
    *
    * @var string
    */
    protected $basePath = __DIR__.'/../../../';

    /**
    * Constructor
    *
    * @param string $basePath
    * @return void
    */
    public function __construct($basePath = null)
    {
        if($basePath)
            $this->setBasePath($basePath);
        
        static::$app = $this;
        $this->forceSingleton('app',$this);
        $this->registerCoreClasses($this->coreClasses());
    }

    /**
    * Create realPath from passed argument, and store it in basePath
    *
    * @param string
    * @throws \InvalidArgumentException
    * @return void
    */
    public function setBasePath($basePath)
    {
        if(!is_dir($basePath))
            throw new \InvalidArgumentException("Directory does not exists: ".$basePath);

        $this->basePath = $basePath;    
    }

    /**    
    * Getting a basePath    
    *    
    * @return $string    
    */    
    public function getBasePath()    
    {    
        return realpath($this->basePath);
    }   

    /**    
    * Getting a value from container  
    *    
    * @param string $key
    * @return mixed    
    */    
    public function __get($key)    
    {    
        return $this->resolve($key);
    }    
    
    /**    
    * Get Core class aliases    
    *    
    * @return array    
    */    
    public function coreClasses()    
    {    
        return [    
            'file'      =>  [
                'className'     =>  'NeuraFrame\FileSystem\File',
                'abstracts'       =>  ['NeuraFrame\Contracts\FileSystem\FileLoaderInterface','NeuraFrame\Contracts\FileSystem\FilePathInterface'],
                'singleton'     =>  false
            ],
            'database_connection' =>[
                'className'     =>  'NeuraFrame\Database\PDOConnection',
                'abstracts'       =>  ['NeuraFrame\Contracts\Database\PDOInterface'],
                'singleton'     =>  true
            ],
            'configuration'     =>[
                'className'     =>  'NeuraFrame\Support\Configuration',
                'abstracts'     =>  ['NeuraFrame\Contracts\Support\Configuration\ConfigDatabaseInterface','NeuraFrame\Contracts\Support\Configuration\ConfigMiddlewareInterface'],
                'singleton'     =>  true
            ],
            'database'  =>[
                'className'     =>  'NeuraFrame\Database\Database',
                'abstracts'     =>  ['NeuraFrame\Contracts\Database\DatabaseInterface'],
                'singleton'     =>  false
            ],
            'router'    =>[
                'className'     =>  'NeuraFrame\Routing\Router',
                'abstracts'     =>  [],
                'singleton'     =>  true
            ],
            'routeFactory'  =>[
                'className'     =>  'NeuraFrame\Routing\RouteFactory',
                'abstracts'     =>  ['NeuraFrame\Contracts\Routing\RouteFactoryInterface'],
                'singleton'     =>  false
            ],
            'controllerFactory' =>[
                'className'     =>  'NeuraFrame\Controller\ControllerFactory',
                'abstracts'     =>  ['NeuraFrame\Contracts\Controller\ControllerFactoryInterface'],
                'singleton'     =>  true
            ],
            'view'          =>[
                'className'     =>  'NeuraFrame\View\TwigTemplateEngine',
                'abstracts'     =>  ['NeuraFrame\Contracts\View\TemplateEngineInterface'],
                'singleton'     =>  false
            ],
            'dbMapper'      =>[
                'className'     =>  'NeuraFrame\Orm\DatabaseMapper',
                'abstracts'     =>  [],
                'singleton'     =>  true
            ],
            'mapperModelFactory'    =>[
                'className'     =>  'NeuraFrame\Orm\MapperModelFactory',
                'abstracts'     =>  ['NeuraFrame\Contracts\Orm\MapperModelFactoryInterface'],
                'singleton'     =>  false
            ]
        ];    
    }}