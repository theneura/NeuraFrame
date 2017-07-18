<?php

namespace NeuraFrame\Middleware;

use RuntimeException;
use NeuraFrame\Contracts\Application\ApplicationInterface;

class MiddlewareFactory
{
    /**
    * Application interface object $app
    *
    * @var \NeuraFrame\Contracts\Application\ApplicationInterface
    */
    private $app;

    /**
    * Middleware aliases container
    *
    * @var array
    */
    private $aliases = array();

    /**
    * Middleware container
    *
    * @var array
    */
    private $middlewares = array();

    /**
    * Constructor
    *
    * @param \NeuraFrame\Contracts\Application\ApplicationInterface $app
    */
    public function __construct(ApplicationInterface $app)
    {
        $this->app = $app;
    }

    /**
    * Get middleware by middleware name
    *
    * @param string $name
    * @return \NeuraFrame\Contracts\Middleware\Handable
    */
    public function middleware($name)
    {
        if(!$this->hasMiddleware($name))
            $this->addMiddleware($name);
        return $this->getMiddleware($name);
    }

    /**
    * Check middleware container by key
    *
    * @param string $name
    * @return bool
    */
    public function hasMiddleware($name)
    {
        return array_key_exists($name,$this->middlewares);
    }

    /**
    * Create new middleware from middleware aliases
    *
    * @param string $name
    * @return bool
    */
    private function addMiddleware($name)
    {
        if(!$this->hasAlias($name))
            throw new RuntimeException("Trying to make middleware ".$name." ,but failed to find it as alias key");
        $alias = $this->getAlias($name);
        $this->middlewares[$name] = new $alias;
    }

    /**
    * Get \NeuraFrame\Contracts\Middleware\Handable object by key
    * 
    * @param string $name
    * @return \NeuraFrame\Contracts\Middleware\Handable
    */
    private function getMiddleware($name)
    {
        return $this->middlewares[$name];
    }

    /**
    * Check does alias name exists as key in aliases container
    *
    * @param string $name 
    * @return bool
    */
    private function hasAlias($name)
    {
        return array_key_exists($name,$this->aliases);
    }

    /**
    * Register user defined aliases for middlewares
    *
    * @param array $aliases
    */
    public function registerAliases($aliases)
    {
        foreach($aliases as $aliasKey => $aliasValue)
        {
            if(!class_exists($aliasValue))
                throw new RuntimeException("Middleware alias class ".$aliasValue." does not exists");
            $this->setAlias($aliasKey,$aliasValue);
        }
    }

    /**
    * Set alias value by key
    *
    * @param string $key
    * @param string $value 
    * @return void
    */
    private function setAlias($key,$value)
    {
        $this->aliases[$key] = $value;
    }

    /**
    * Get alias value by key
    *
    * @param string $key
    * @return string
    */
    private function getAlias($key)
    {
        return $this->aliases[$key];
    }
}