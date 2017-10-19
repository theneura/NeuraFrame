<?php

namespace NeuraFrame\Controller;

use NeuraFrame\Contracts\Controller\ControllerFactoryInterface;
use NeuraFrame\Contracts\Application\ApplicationInterface;
use NeuraFrame\Contracts\Routing\RouteControllerInterface;
use NeuraFrame\Http\Request;
use NeuraFrame\Containers\Controller\ControllerCollection;

use NeuraFrame\Exceptions\ControllerNotExistsException;
use NeuraFrame\Exceptions\ClassNotExistsException;
use NeuraFrame\Exceptions\MethodIsNotCallableException;

class ControllerFactory implements ControllerFactoryInterface
{
    /**
    * Instance of the application container
    *
    * @var NeuraFrame\Containers\Application\Container
    */
    private $app;

    /**
    * Array of controllers
    *
    * @var NeuraFrame\Containers\Controller\ControllerCollection
    */
    private $controllers;

    /**
    * ControllerFactory constructor
    *
    * @param NeuraFrame\Contracts\Application\ApplicationInterface $app
    */
    public function __construct(ApplicationInterface $app,ControllerCollection $controllers)
    {
        $this->app = $app;
        $this->controllers = $controllers;
    }

    /**
    * Call the require controller method
    * 
    * @param NeuraFrame\Contracts\Routing\RouteControllerInterface $route
    * @throws NeuraFrame\Exceptions\MethodIsNotCallableException
    * @return mixed
    */
    public function getProperController(RouteControllerInterface $route)
    {
        $controllerObject = $this->controller($route->controllerAlias());

        if(!is_callable([$controllerObject,$route->controllerMethod()]))
            throw new MethodIsNotCallableException($route->controllerAlias(),$route->controllerMethod());
        
        return $controllerObject;
    }

    /**
    * Return required controller
    *
    * @param string $controllerAlias
    * @return object
    */
    private function controller($controllerAlias)
    {
        $controllerClassName = $this->getControllerClassName($controllerAlias);

        if(!$this->hasController($controllerClassName))
            $this->addController($controllerClassName);
        
        return $this->getController($controllerClassName);
    }

    /**
    * Get controller object
    *
    * @param string $controllerClassName
    * @throws NeuraFrame\Exceptions\ControllerNotExistsException
    * @return Object[$controllerClassName]
    */
    private function getController($controllerClassName)
    {
        if(!$this->hasController($controllerClassName))
            throw new ControllerNotExistsException($controllerClassName);
        
        return $this->controllers[$controllerClassName];
    }

    /**
    * Get controller class name with namespaces
    *
    * @param string $controllerAlias
    * @return string
    */
    private function getControllerClassName($controllerAlias)
    {
        return str_replace('/','\\','App\\Controllers\\'.$controllerAlias);
    }

    /**
    * Determine if the given controllerClassName exists as controller in array
    *
    * @param string $controllerClassName
    * @return bool
    */
    private function hasController($controllerClassName)
    {
        return $this->controllers->offsetExists($controllerClassName);
    }

    /**
    * Add new controller to array
    *
    * @param string $controllerClassName
    * @return void
    */
    private function addController($controllerClassName)
    {
        $controller = $this->createNewControllerObject($controllerClassName);
        $this->controllers[$controllerClassName] = $controller;
    }

    /**
    * Creates new controller object from class name
    *
    * @param string $controllerClassName
    * @throws NeuraFrame\Exceptions\ClassNotExistsException
    * @return Object[$controllerClassName]
    */
    private function createNewControllerObject($controllerClassName)
    {
        if(!class_exists($controllerClassName))
            throw new ClassNotExistsException($controllerClassName);
        
        return new $controllerClassName($this->app);
    }
}