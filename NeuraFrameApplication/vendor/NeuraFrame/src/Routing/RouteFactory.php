<?php

namespace NeuraFrame\Routing;

use NeuraFrame\Containers\Routing\RouteCollection;
use NeuraFrame\Exceptions\RouteExistsException;
use NeuraFrame\Contracts\Routing\RouteFactoryInterface;
use NeuraFrame\Http\Request;

class RouteFactory implements RouteFactoryInterface
{
    /**
    * Collection of routes
    *
    * @var NeuraFrame\Containers\Routing\RouteCollection $routes
    */
    private $routes;

    /**
    * Constructor
    *
    * @param NeuraFrame\Containers\Routing\RouteCollection $routes;
    */
    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    /**
    * Adding new route
    *
    * @param string $urlWithExpectedArguments
    * @param string $requestMethod
    * @param string $controllerAliasAndMethod
    * @return NeuraFrame\Routes\Route
    */
    public function addRoute($urlWithExpectedArguments,$requestMethod,$controllerAliasAndMethod)
    {
        return $this->createNewRoute($urlWithExpectedArguments,$requestMethod,$controllerAliasAndMethod);
    }

    /**
    * Creating new route from passed arguments
    *
    * @param string $urlWithExpectedArguments
    * @param string $requestMethod
    * @param string $controllerAliasAndMethod
    * @return NeuraFrame\Routes\Route;
    */
    private function createNewRoute($urlWithExpectedArguments,$requestMethod,$controllerAliasAndMethod)
    {
        $urlWithExpectedArguments = explode(':',$urlWithExpectedArguments);
        $cleanUrl = array_shift($urlWithExpectedArguments);

        if($this->routeExists($cleanUrl))
            throw new RouteExistsException($cleanUrl);
        
        
        $this->routes[$cleanUrl] = new Route($cleanUrl,$requestMethod,$controllerAliasAndMethod,$urlWithExpectedArguments);
        return $this->routes[$cleanUrl];
    }

    /**
    * Get proper route
    *
    * @param NeuraFrame\Http\Request $request
    * @return NeuraFrame\Routes\Route
    */
    public function getProperRoute(Request $request)
    {
        if($this->routeExists($request->url()))
            return $this->getRoute($request);
        
        throw new \OutOfBoundsException("Current requested route does not exists"); 
    }

    /**
    * Searching for correct route
    *
    * @param \NeuraFrame\Http\Request $request
    * @return \NeuraFrame\Routing\Route $route
    */
    private function getRoute(Request $request)
    {
        if($this->isMatching($this->routes[$request->url()],$request))
            return $this->routes[$request->url()];

        throw new \OutOfBoundsException("Current requested route is not matching with any of the routes in array"); 
    }

    /**
    * Determine if the given pattern maches the current request
    *
    * @param \NeuraFrame\Routing\Route $route
    * @param \NeuraFrame\Http\Request $request;
    * @return bool
    */
    private function isMatching(Route $route,Request $request)
    {
        if($route->urlIsMatching($request->url()) && $route->methodIsMatching($request->method()) && $route->argumentsMatch($request->getAll()))
            return true;
        
        return false;
    }

    /**
    * Determine if the given url exists as route
    *
    * @param string $url
    * @return bool
    */
    private function routeExists($url)
    {        
        return $this->routes->offsetExists($url);
    }

}