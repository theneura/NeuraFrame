<?php

namespace NeuraFrame\Exceptions;

use Exception;

class ControllerNotExistsException extends Exception 
{
    public function __construct($controllerName)
    {
        parent::__construct('Controller <b>'.$controllerName. '</b> does not exists!');
    }
}