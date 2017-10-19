<?php

namespace NeuraFrame\Exceptions;

use Exception;

class VariableIsNotSetException extends Exception
{
    /**
    * Constructor
    *
    * @param string $message
    * @return void
    */
    public function __construct($message)
    {
        parent::__construct('Required variable is not set! : '.$message);
    }
}