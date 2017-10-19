<?php

namespace NeuraFrame\Routing;

use NeuraFrame\Contracts\Application\ApplicationInterface;
use NeuraFrame\Contracts\Routing\RouteFactoryInterface;
use NeuraFrame\Contracts\Controller\ControllerFactoryInterface;
use NeuraFrame\Http\Request;
use NeuraFrame\Controller;
use NeuraFrame\Middleware\Pipeline;


class Router
{
    /**
    * Instance of the application interface
    *
    * @var NeuraFrame\Contracts\Application\ApplicationInterface $app;
    */
    private $app;

    /**
    * Instance of route factory
    *
    * @var NeuraFrame\Contracts\Routing\RouteFactoryInterface
    */
    private $routeFactory;

    /**
    * Instance of controller factory 
    *
    * @var NeuraFrame\Contracts\Controller\ControllerFactoryInterface
    */
    private $controllerFactory;

    /**
    * Router constructor
    *
    * @param NeuraFrame\Contracts\Application\ApplicationInterface $app
    * @param NeuraFrame\Contracts\Routing\RouteFactoryInterface $routeFactory
    * @param NeuraFrame\Contracts\Controller\ControllerFactoryInterface $controllerFactory
    */
    public function __construct(ApplicationInterface $app,RouteFactoryInterface $routeFactory,ControllerFactoryInterface $controllerFactory)
    {
        $this->app = $app;
        $this->routeFactory = $routeFactory;
        $this->controllerFactory = $controllerFactory;
    }

    /**
    * Handling passed request, get proper route and controller, return response through pipeline 
    *
    * @param NeuraFrame\Http\Request $request
    * @return mixed
    */
    public function handleRequest(Request $request)
    {
        $route = $this->routeFactory->getProperRoute($request);
        $controllerObject = $this->controllerFactory->getProperController($route);
        
        $pipeline = new Pipeline($this->app);

        return $pipeline->sendRequest($request)
                        ->setMiddlewares($route->hasMiddleware() ? $route->getMiddlewares() : null)
                        ->then(function() use ($controllerObject, $route, $request){
                            return $this->callControllerMethod($controllerObject,$route->controllerMethod(),$request);
                        });
    }

    /**
    * Calling controller method
    *
    * @param NeuraFrame\Controller $controllerObject
    * @param string $method
    * @param NeuraFrame\Http\Request $request
    */
    private function callControllerMethod(Controller $controllerObject,$method,Request $request)
    {
        return call_user_func_array([$controllerObject,$method],array_merge(array($request),$request->getAll()));
    }

    /**
    * Add new route to route collection
    *
    * @param string $urlWithExpectedArguments
    * @param string $controllerAliasAndMethod
    * @param string $requestMethod
    * @return NeuraFrame\Routing\Route
    */
    public function addRoute($urlWithExpectedArguments,$controllerAliasAndMethod,$requestMethod = null)
    {
        $requestMethod = $requestMethod ?: 'GET';
        return $this->routeFactory->addRoute($urlWithExpectedArguments,$requestMethod,$controllerAliasAndMethod);
    }
}