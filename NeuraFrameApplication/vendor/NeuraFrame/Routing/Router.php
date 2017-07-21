<?php

namespace NeuraFrame\Routing;

use NeuraFrame\Session;
use NeuraFrame\Contracts\Application\ApplicationInterface;

class Router
{
    /**
    * Application Object container
    *
    * @var \NeuraFrame\ApplicationInterface
    */
    private $app;

    /**
    * Route collection Object
    *
    * @var \NeuraFrame\Routing\RouteCollection
    */
    private $routes;    

    /**
    * Last called route
    *
    * @var \NeuraFrame\Routing\Route
    */
    public $lastRoute;

    /**
    * Constructor
    *
    * @param \NeuraFrame\ApplicationInterface $app 
    */
    public function __construct(ApplicationInterface $app)
    {
        $this->app = $app;
        $this->routes = new RouteCollection;
    }

    /**
    * Handle current request, get route, load controller, call controller function and output respond
    *
    * @return mixed
    */
    public function getResponseContent()
    {
       $route = $this->routes->getProperRoute($this->app->request);
       if($route->hasMiddleware())
           $this->app->middlewareFactory->middleware($route->getMiddleware())->handle($this->app->request);

       $this->lastRoute = $route;
       return $this->app->controllerFactory->handleController($route,$this->app->request);
    }

    public function lateUpdate()
    {
        Session::set('lastRoute',$this->lastRoute);
    }

    /**
    * Get route by name
    *
    * @param string $name
    * @return \NeuraFrame\Routing\Route
    */
    public function route($name)
    {
        return $this->routes->getByName($name);
    }
    

    /**
    * Add new route
    *
    * @param string $url
    * @param string $action
    * @param string $requestMethod
    * @return NeuraFrame\Routing\Route
    */
    public function addRoute($url,$action,$requestMethod = 'GET')
    {
        $fullUrl = $this->app->request->getFullUrlFromBase($url);
        return $this->routes->add($url,$requestMethod,$action);
    }    
}