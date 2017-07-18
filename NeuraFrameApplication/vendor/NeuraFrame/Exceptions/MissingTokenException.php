<?php

namespace NeuraFrame\Exceptions;

use Exception;

class MissingTokenException extends Exception 
{
    public function __construct()
    {
        parent::__construct('CSRF token is missing while sending a post method');
    }
}