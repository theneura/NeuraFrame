<?php

namespace NeuraFrame\Contracts\FileSystem;

interface FilePathInterface
{
    /**    
    * Generate full path to the given path in vendor folder    
    *    
    * @param string $path     
    * @return string    
    */    
    public function toVendor($path = '');    

    /**    
    * Generate full path to the given path in NeuraFrame folder    
    *    
    * @param string $path     
    * @return string    
    */    
    public function toNeuraFrame($path = '');   

    /**    
    * Generate full path to App folder    
    *    
    * @param string $path    
    * @return string    
    */    
    public function toApp($path = '');    

    /**    
    * Generate full path to Public directory    
    *    
    * @param string $path     
    * @return string    
    */    
    public function toPublic($path = '');   

    /**    
    * Generate full path to Uploads directory    
    *    
    * @param string $path     
    * @return string    
    */    
    public function toUploads($path = '');   
}