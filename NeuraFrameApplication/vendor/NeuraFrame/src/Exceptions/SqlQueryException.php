<?php

namespace NeuraFrame\Exceptions;

use Exception;

class SqlQueryException extends Exception
{
    /**
    * Constructor
    *
    * @param string $message
    * @return void
    */
    public function __construct($message)
    {
        parent::__construct('SQL query exception: '.$message);
    }
}