<?php
namespace NeuraFrame\Exceptions;

use Exception;

class FileFailUploadingException extends Exception 
{
    public function __construct($fileErrors = array())
    {
        $error = implode(',',$fileErrors);
        parent::__construct('Fail while uploading a file <b>'.$error. '</b> !');
    }
}