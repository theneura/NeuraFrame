<?php

namespace NeuraFrame\Routing;

use NeuraFrame\Http\Request;

class Route 
{
    /**
    * Url value for given route
    *
    * @var string
    */
    public $url;

    /**
    * Method for given route
    *
    * @var string
    */
    public $method;

    /**
    * The route request method
    *
    * @var string
    */
    public $requestMethod;

    /**
    * Route name
    *
    * @var string
    */
    private $name;

    /**
    * Middleware name
    *
    * @var string
    */
    private $middleware;

    /**
    * Controller instance
    *
    * @var string
    */
    public $controller;

    /**
    * Expected arguments from url
    *
    * @var array
    */
    public $expectedArguments = [];

    /**
    * Constructor
    * 
    * @param string $url
    * @param string $requestMethod
    * @param string $methodAndController
    */
    public function __construct($url,$requestMethod,$methodAndController)
    {
        $this->url = $url;
        $this->requestMethod = $requestMethod;
        list($this->controller,$this->method) = explode('@',$this->getMethodAndController($methodAndController));
    }

    /**
    * Adding middleware to current route
    *
    * @param string $name
    */
    public function middleware($name)
    {
        $this->middleware = $name;
        return $this;
    }

    /**
    * Get route middleware name
    *
    * @return string
    */
    public function getMiddleware()
    {
        return $this->middleware;
    }

    /**
    * Check does middleware name is set
    *
    * @return bool
    */
    public function hasMiddleware()
    {
        return isset($this->middleware);
    }

    /**
    * Get the proper method and controller
    *
    * @param string $methodAndController
    * @return string
    */
    private function getMethodAndController($methodAndController)
    {
        $methodAndController = str_replace('/','\\',$methodAndController);
        return strpos($methodAndController,'@') !== false ? $methodAndController : $methodAndController.'@index';
    }

    /**
    * Set name of the route
    *
    * @param string $name
    */
    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
    * Get name of the route
    *
    * @return string $name
    */
    public function getName()
    {
        return $this->name;
    }

    public function getCleanUrl()
    {
        return substr($this->url,1,strpos($this->url,':') - 2);
    }

    public function setExpectedArguments($expectedArguments = array())
    {
        $this->expectedArguments = $expectedArguments;
    }

    public function getExpectedArguments()
    {
        return $this->expectedArguments;
    }


    /**
    * Check if passed url is same as route url
    *
    * @param string $url
    * @return bool
    */
    public function urlIsMatch($url)
    {
        return $this->url === $url;
    }

    /**
    * Check if passed method is same as route method
    *
    * @param string $method
    * @return bool
    */
    public function methodIsMatch($method)
    {
        return $this->requestMethod === $method;
    }
}