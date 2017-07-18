<?php

namespace NeuraFrame\Containers;

use RuntimeException;
use NeuraFrame\Exceptions\ClassNotExistsException;

class Container
{
    /**
    * The instance of the object
    * 
    * @var static
    */
    protected static $instance;

    /**
    * An array of the instances shared objects
    *
    * @var array
    */
    protected $sharedInstances = [];

    /**
    * An array of type aliases
    *
    * @var array
    */
    protected $aliases = [];

    /**
     * The base path for NeuraFrame
     *
     * @var string
     */
    protected $basePath = __DIR__.'/../../../';

    /**
    * Set globaly available istance of the object
    *
    * @param string $basepath
    * @return static
    */
    public static function getInstance()
    {
        if(is_null(static::$instance))
            static::$instance = new static();
        return static::$instance;
    }
    

    /**
    * Set the globaly available instance of the container
    *
    * @param NeuraFrame\Containers\Container $container
    * @return static
    */
    protected static function setInstance(Container $instance = null)
    {
        return static::$instance = $instance;
    }    

    /**
    * Share the given key\value through Application
    * @param string $key
    * @param mixed $value
    * @return mixed
    */
    protected function share($key,$value)
    {
        $this->sharedInstances[$key] = $value;
    }

    /**
    * Set alias by key to array aliases
    *
    * @param string $key
    * @param string $alias 
    * @return void
    */
    protected function setAlias($key,$alias)
    {
        if(!class_exists($alias))
            throw new ClassNotExistsException($alias);
        $this->aliases[$key] = $alias;
    }

    /**
    * Get shared value from container
    * @param string $key
    * @return mixed
    */
    protected function getSharedInstance($key)
    {
        if(!$this->isSharingInstance($key))
            $this->setSharedInstance($key);
        return $this->sharedInstances[$key];
    } 

    /**
    * Register new shared values inside sharedInstance array
    *
    * @var string $key
    * @return void
    */
    protected function setSharedInstance($key)
    {
        if($this->isAlias($key))
        {
            $this->share($key,$this->createNewCoreObject($key));
            return;
        }
        throw new RuntimeException($key.' not found in application container');
    }

    /**
    * Create new core object based on aliases key
    * 
    * @param string $key
    * @return object
    */
    private function createNewCoreObject($key)
    {
        $object = $this->aliases[$key];
        return new $object($this);
    }

    /**
    * Determine if the given key is inside container values
    * @param string $key
    * @return bool
    */
    private function isSharingInstance($key)
    {
        return isset($this->sharedInstances[$key]);
    }

    /**
    * Determine if the given key is inside aliases array
    * @param string $key
    * @return bool
    */
    private function isAlias($key)
    {
        return isset($this->aliases[$key]);
    }

    /**
    * Set base path for application
    *
    * @param string $basePath
    */
    protected function setBasePath($basePath)
    {
        $this->basePath = str_replace('/','\\',$basePath);
    }
}
