<?php

namespace NeuraFrame\Exceptions;

use Exception;

class FileNotExistsException extends Exception 
{
    public function __construct($filePath)
    {
        parent::__construct('File <b>'.$filePath. '</b> does not exists!');
    }
}