<?php

namespace NeuraFrame\Contracts\Routing;

use NeuraFrame\Http\Request;

interface RouteFactoryInterface
{
    /**
    * Get proper route by given Request object
    *
    * @param NeuraFrame\Http\Request $request
    * @return NeuraFrame\Routing\Route $route
    */
    public function getProperRoute(Request $request);
}