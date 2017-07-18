<?php

namespace NeuraFrame\Routing;

use Countable;
use ArrayIterator;
use IteratorAggregate;
use RuntimeException;
use InvalidArgumentException;
use NeuraFrame\Http\Request;

class RouteCollection implements Countable, IteratorAggregate
{
    /**
     * An array of the routes
     *
     * @var array
     */
    protected $routes = [];

    /**
    * Add new Route instance to collection
    *
    * @param string $url
    * @param \NeuraFrame\Routing\Route $route
    * @return \NeuraFrame\Routing\Route
    */
    public function add($url,$requestMethod,$action)
    {
        $urlWithExpectedArguments = explode(':',$url);
        $url = array_shift($urlWithExpectedArguments);

        if(array_key_exists($url,$this->getRoutes()))
            throw new RuntimeException('Route: '.$url.' redeclaring exception');
        
        $route = new Route($url,$requestMethod,$action);
        $route->setExpectedArguments($urlWithExpectedArguments);
        $this->routes[$url] = $route;
        return $route;
    }

    /**
    * Get route by name
    *
    * @param string $routeName
    * @return \NeuraFrame\Routing\Route
    */
    public function getByName($routeName)
    {
        foreach($this->getRoutes() as $route)
        {
            if($route->getName() == $routeName){
                return $route;
            }
        }
        throw new RuntimeException($routeName." cannot be found in RouteCollection");
    }

    /**
    * Get all routes from collection
    *
    * @return array
    */
    public function getRoutes()
    {
        return array_values($this->routes);
    }

    /**
    * Get proper route
    *
    * @param \NeuraFrame\Http\Request $request
    * @return \NeuraFrame\Routing\Route
    */
    public function getProperRoute(Request $request)
    {
        if($this->routeExists($request->url()))
            return $this->searchForCorrectRoute($request);  

        throw new RuntimeException("Requested route does not exists as key value in routes array");
    }

    /**
    * Detrermine if route is register as routes key
    *
    * @param string $requestUrl
    * @return bool
    */
    private function routeExists($requestUrl)
    {
        return array_key_exists($requestUrl,$this->routes);
    }

    /**
    * Searching for correct route
    *
    * @param \NeuraFrame\Http\Request $request
    * @return \NeuraFrame\Routing\Route $route
    */
    private function searchForCorrectRoute(Request $request)
    {
        foreach($this->getRoutes() as $route)
        {            
            if($this->isMatching($route,$request))
            {
                return $route;
            }
        }
        throw new RuntimeException("Current route is not matching with any of the routes in array"); 
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
        if($route->urlIsMatch($request->url()) && $route->methodIsMatch($request->method()))
            return $this->checkArguments($route,$request);
    }

    /**
    * Check if Route expected arguments are same as request passed arguments
    *
    * @param \NeuraFrame\Routing\Route $route
    * @param \NeuraFrame\Http\Request $request
    */
    private function checkArguments(Route $route,Request $request)
    {
        $expectedArguments = $route->getExpectedArguments();
        $passedArguments = $request->getAll();
        if(sizeof($expectedArguments) === sizeof($passedArguments))
        {
            foreach($passedArguments as $passedArgumentKey => $passedArgumentValue)
            {
                if(!in_array($passedArgumentKey,$expectedArguments))
                    throw new InvalidArgumentException($passedArgumentKey." query argument value is not expected");
            }
            return true;
        }
        return false;
    }

    /**
    * Get an iterator for the item
    *
    * @return \ArrayIterator
    */
    public function getIterator()
    {
        return new ArrayIterator($this->getRoutes());
    }

    /**
    * Count the number of items in the collection of routes
    *
    * @return int
    */
    public function count()
    {
        return count($this->getRoutes());
    }
}