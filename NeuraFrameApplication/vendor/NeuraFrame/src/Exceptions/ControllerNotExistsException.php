<?php

namespace NeuraFrame\Exceptions;

use Exception;

class ControllerNotExistsException extends Exception 
{
    public function __construct($controller)
    {
        parent::__construct('Controller: <b>'.$controller. '</b> does not eixsts! Please check your controllers!');
    }
}