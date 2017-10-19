<?php

namespace NeuraFrame\Contracts\FileSystem;

interface FileLoaderInterface
{
    /**    
    * Require file from given path    
    *    
    * @param $filePath     
    * @return mixed    
    */    
    public function require($filePath);  
}