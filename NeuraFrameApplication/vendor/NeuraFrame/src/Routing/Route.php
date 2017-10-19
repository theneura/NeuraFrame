<?php

namespace NeuraFrame\Routing;

use NeuraFrame\Contracts\Routing\RouteControllerInterface;
use NeuraFrame\Exceptions\ClassNotExistsException;

class Route implements RouteControllerInterface
{
    /**
    * Url for current route
    *
    * @var string
    */
    private $url;

    /**
    * Expected request method
    *
    * @var string
    */
    private $requestMethod;

    /**
    * Controller alias of current route
    *
    * @var string
    */
    private $controllerAlias;

    /**
    * Array of middleware names for this route
    *
    * @var array
    */
    private $middlewares = array();

    /**
    * Controller method
    *
    * @var string
    */
    private $controllerMethod;

    /**
    * Name of the current route
    *
    * @var string
    */
    private $name;

    /**
    * Array of expected arguments at this route
    *
    * @var array
    */
    private $expectedArguments;

    /**
    * Constructor
    *
    * @param string $url
    * @param string $requestMethod
    * @param string $controllerAliasAndMethod
    * @param array $expectedArguments
    */
    public function __construct($url,$requestMethod,$controllerAliasAndMethod,array $expectedArguments = null)
    {
        $this->url = $url;
        $this->requestMethod = $requestMethod;
        list($this->controllerAlias,$this->controllerMethod) = explode('@',$this->parseControllerAliasAndMethod($controllerAliasAndMethod));

        if($expectedArguments)
            $this->expectedArguments = $expectedArguments;
    }

    /**
    * Return controller alias for this route
    *
    * @return string
    */
    public function controllerAlias()
    {
        return $this->controllerAlias;
    }

    /**
    * Adding middlewareClassAlias for routes
    *
    * @throws NeuraFrame\Exceptions\ClassNotExistsException
    * @var string $middlewareName
    */
    public function addMiddleware($middlewareClassAlias)
    {
        $middlewareClassAlias = 'App\\Middleware\\'.$middlewareClassAlias;

        if(!class_exists($middlewareClassAlias))
            throw new ClassNotExistsException($middlewareClassAlias);

        $this->middlewares[] = $middlewareClassAlias;
        return $this;
    }

    /**
    * Determine if middleware array is empty or not
    *
    * @return bool
    */ 
    public function hasMiddleware()
    {
        return !empty($this->middlewares);
    }

    /**
    * Return middleware array
    *
    * @return array
    */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    /**
    * Determine if passed arguments are same as expected ones
    *
    * @param array $arguments
    * @return bool
    */
    public function argumentsMatch(array $arguments)
    {
        if(sizeof($this->expectedArguments) !== sizeof($arguments))
            return false;

        foreach($arguments as $argument)
            if(!in_array($argument,$this->expectedArguments))
                throw new \InvalidArgumentException($argument. " is not expected for route: ".$this->url);

        return true;
    }

    /**
    * Set name for current route
    *
    * @param string $name
    */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
    * Get name of the current route
    *
    * @return string
    */
    public function name()
    {
        return $this->name;
    }

    /**
    * Return controller method for this route
    *
    * @return string
    */
    public function controllerMethod()
    {
        return $this->controllerMethod;
    }

    /**
    * Parse controller alias and method, check if method is not set and put @index instead, and return resoult in forme controllerAlias@controllerMethod
    *
    * @param string $controllerAliasAndMethod
    * @return string
    */
    private function parseControllerAliasAndMethod($controllerAliasAndMethod)
    {
        $controllerAliasAndMethod = str_replace('/','\\',$controllerAliasAndMethod);
        return strpos($controllerAliasAndMethod,'@') !== false ? $controllerAliasAndMethod : $controllerAliasAndMethod.'@index';
    }

    /**
    * Check if passed url is same as route url
    *
    * @param string $url 
    * @return bool
    */
    public function urlIsMatching($url)
    {
        return $this->url === $url;
    }

    /**
    * Check if passed request method is same as expected
    *
    * @param string $requestMethod 
    * @return bool
    */
    public function methodIsMatching($requestMethod)
    {
        return $this->requestMethod === $requestMethod;
    }
}