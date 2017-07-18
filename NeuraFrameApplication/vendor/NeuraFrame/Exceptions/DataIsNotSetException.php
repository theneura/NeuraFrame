<?php

namespace NeuraFrame\Exceptions;

use Exception;

class DataIsNotSetException extends Exception 
{
    public function __construct($dataKey)
    {
        parent::__construct('Data <b>'.$dataKey. '</b> is not set!');
    }
}