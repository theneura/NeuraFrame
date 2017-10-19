<?php

namespace NeuraFrame\Exceptions;

use Exception;

class MethodIsNotCallableException extends Exception 
{
    public function __construct($controllerAlias,$controllerMethod)
    {
        parent::__construct('Object of controller class: <b>'.$controllerAlias. '</b> has Method: <b> '.$controllerMethod.'</b> that is not callable! Please check your method!');
    }
}