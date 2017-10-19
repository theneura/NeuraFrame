<?php

namespace NeuraFrame\Exceptions;

use Exception;

class DatabaseConnectionException extends Exception
{
    /**
    * Constructor
    *
    * @param string $message
    * @return void
    */
    public function __construct($message)
    {
        parent::__construct('Database Connection exception: '.$message);
    }
}