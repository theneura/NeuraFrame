<?php

namespace NeuraFrame\Exceptions;

use Exception;

class ClassNotExistsException extends Exception 
{
    public function __construct($className)
    {
        parent::__construct('Class <b>'. $className .'</b> does not exist!');
    }
}