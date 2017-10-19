<?php

namespace NeuraFrame\Support;

use NeuraFrame\Contracts\FileSystem\FileLoaderInterface;
use NeuraFrame\Contracts\Support\Configuration\ConfigDatabaseInterface;
use NeuraFrame\Contracts\Support\Configuration\ConfigMiddlewareInterface;
use NeuraFrame\Support\ArrayContainer;
use OutOfBoundsException;

class Configuration extends ArrayContainer implements ConfigDatabaseInterface, ConfigMiddlewareInterface
{
    /**
    *    
    * @param \NeuraFrame\Contracts\FileSystem\FileLoaderInterface    
    * @return void    
    */    
    public function __construct(FileLoaderInterface $fileLoader)    
    {    
        $this['database'] = $fileLoader->require('config/database.php');    
        $this['middleware'] = $fileLoader->require('config/middleware.php');    
    }    

    /**    
    * Return database configurations    
    *    
    * @return array    
    */    
    public function getDatabaseConfig()    
    {    
        if(!isset($this['database']) || !is_array($this['database']))    
            throw new OutOfBoundsException('Database Configuration is not set');    
            
        return $this['database'];    
    }    

    /**    
    * Return middleware configurations    
    *    
    * @return array    
    */    
    public function getMiddlewareConfig()    
    {    
        if(!isset($this['middleware']) || !is_array($this['middleware']))    
            throw new OutOfBoundsException('Middleware Configuration is not set');    
            
        return $this['middleware'];    
    }
}