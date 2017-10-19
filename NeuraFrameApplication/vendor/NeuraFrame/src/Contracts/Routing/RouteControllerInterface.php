<?php

namespace NeuraFrame\Contracts\Routing;

interface RouteControllerInterface
{
    /**
    * Return controller alias for this route
    *
    * @return string
    */
    public function controllerAlias();

    /**
    * Return controller method for this route
    *
    * @return string
    */
    public function controllerMethod();
}