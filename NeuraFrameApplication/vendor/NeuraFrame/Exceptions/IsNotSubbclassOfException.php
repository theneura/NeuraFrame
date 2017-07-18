<?php

namespace NeuraFrame\Exceptions;

use Exception;

class IsNotSubbclassOfException extends Exception 
{
    public function __construct($class,$subbclass)
    {
        parent::__construct('Object class <b>'. $class .'</b> is not subbclass of '.$subbclass.'!');
    }
}