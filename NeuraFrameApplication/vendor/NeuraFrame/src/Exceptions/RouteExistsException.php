<?php

namespace NeuraFrame\Exceptions;

use Exception;

class RouteExistsException extends Exception 
{
    public function __construct($route)
    {
        parent::__construct('Route: <b>'.$route. '</b> already exists. You cant define two routes with same url!');
    }
}