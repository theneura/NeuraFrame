<?php

namespace NeuraFrame\Contracts\Controller;

use NeuraFrame\Contracts\Routing\RouteControllerInterface;

interface ControllerFactoryInterface
{
    /**
    * Call the require controller method
    * 
    * @param NeuraFrame\Contracts\Routing\RouteControllerInterface $route
    * @throws NeuraFrame\Exceptions\MethodIsNotCallableException
    * @return mixed
    */
    public function getProperController(RouteControllerInterface $route);
}