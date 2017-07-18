<?php

namespace NeuraFrame\Exceptions;

use Exception;

class InvalidTokenException extends Exception 
{
    public function __construct()
    {
        parent::__construct('CSRF token is not valid');
    }
}