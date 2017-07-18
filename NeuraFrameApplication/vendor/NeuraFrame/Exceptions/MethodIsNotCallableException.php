<?php

namespace NeuraFrame\Exceptions;

use Exception;

class MethodIsNotCallableException extends Exception 
{
    public function __construct($ObjectMethod = array())
    {
        parent::__construct('Method <b>'. implode(' => ',$ObjectMethod) .'</b> is not callable!');
    }
}