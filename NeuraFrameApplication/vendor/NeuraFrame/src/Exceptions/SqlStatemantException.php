<?php

namespace NeuraFrame\Exceptions;

use Exception;

class SqlStatemantException extends Exception
{
    /**
    * Constructor
    *
    * @param string $statemant
    * @param string $message
    * @return void
    */
    public function __construct($statemant,$message)
    {
        parent::__construct('SQL Statemant: '.$statemant. ' Message: '.$message);
    }
}