<?php

namespace NeuraFrame\Exceptions;

use Exception;

class ClassDontHaveInterfaceException extends Exception 
{
    public function __construct($className,$classInterface)
    {
        parent::__construct('Class <b>'. $className .'</b> does not have interface <b>'.$classInterface.'</b>');
    }
}