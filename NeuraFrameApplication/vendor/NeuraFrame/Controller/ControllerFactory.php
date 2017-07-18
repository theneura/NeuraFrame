<?php

namespace NeuraFrame\Controller;

use NeuraFrame\Routing\Route;
use NeuraFrame\Http\Request;
use NeuraFrame\Exceptions\ClassNotExistsException;
use NeuraFrame\Exceptions\ControllerNotExistsException;
use NeuraFrame\Exceptions\MethodIsNotCallableException;
use NeuraFrame\Contracts\Application\ApplicationInterface;


class ControllerFactory 
{
    /**
    * Application Container object
    *
    * @var \NeuraFrame\ApplicationInterface
    */
    private $app;

    /**
    * Controllers container
    *
    * @var array
    */
    private $controllers = [];    

    /**
    * Constructor
    *
    * @param \NeuraFrame\ApplicationInterface $app
    */
    public function __construct(ApplicationInterface $app)
    {
        $this->app = $app;
    }

    /**
    * Call the given controller with the given method
    * and pass the given arguments to the controller method
    * 
    * @param \NeuraFrame\Routing\Route $route
    * @param \NeuraFrame\Http\Request $request
    * @throws \NeuraFrame\Exceptions\MethodIsNotCallableException
    * @return mixed
    */
    public function handleController(Route $route,Request $request)
    {
        $this->app->request->checkToken();              
        $controllerObject = $this->controller($route->controller);

        if(!is_callable([$controllerObject,$route->method]))
            throw new MethodIsNotCallableException([$route->controller,$route->method]);

        return call_user_func_array([$controllerObject,$route->method],array_merge(array($this->app->request),$request->getAll()));
    }

    /**
    * Call the given controller
    * 
    * @param string $controller 
    * @return object
    */
    public function controller($controller)
    {
        $controller = $this->getControllerClass($controller);
        if(!$this->hasController($controller))
            $this->addController($controller);
        return $this->getController($controller);
    }

    /**
    * Get the full class name for the given controller
    * 
    * @param string $controller
    * @return string
    */
    private function getControllerClass($controller)
    {
        return str_replace('/','\\','App\\Controllers\\' . $controller);
    }

    /**
    * Determine if the given class|controller exists
    * in the controllers container
    * 
    * @param string $controller 
    * @return bool
    */
    private function hasController($controller)
    {
        return array_key_exists($controller,$this->controllers);
    }

    /**
    * Load controller and create new object for the given controller
    * 
    * 
    * @param string $controller    
    * @return void
    */
    private function addController($controllerClass)
    {
        $controller = $this->createNewConntrollerObject($controllerClass);            
        $this->controllers[$controllerClass] = $controller;
    }

    /**
    * Creates new object from controll class
    *
    * @param string $controllerClass
    * @throws \NeuraFrame\Exceptions\ClassNotExistsException::class
    * @return Object[$controller]
    */
    private function createNewConntrollerObject($controllerClass)
    {
        if(!class_exists($controllerClass))
            throw new ClassNotExistsException($controllerClass);
        return new $controllerClass($this->app);
    }

    /**
    * Get the controller object
    * 
    * @param string $controller 
    * @throws \NeuraFrame\Exceptions\ControllerNotExistsException
    * @return object
    */
    private function getController($controller)
    {
        if(!array_key_exists($controller,$this->controllers))
            throw new ControllerNotExistsException($controller);
        return $this->controllers[$controller];
    }    
}