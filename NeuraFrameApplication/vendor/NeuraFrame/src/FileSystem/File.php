<?php

namespace NeuraFrame\FileSystem;

use InvalidArgumentException;
use NeuraFrame\Exceptions\FileNotExistsException;
use NeuraFrame\Contracts\FileSystem\FileLoaderInterface;
use NeuraFrame\Contracts\FileSystem\FilePathInterface;
use NeuraFrame\Contracts\Application\ApplicationInterface;

class File implements FileLoaderInterface, FilePathInterface
{
    /**
    * NeuraFrame application base path
    *
    * @var string
    */
    protected $basePath;

    /**
    * Directory separator
    *
    * @var string
    */
    const DS = DIRECTORY_SEPARATOR;

    /**
    * Constructor
    *
    * @param NeuraFrame\Contracts\Application\ApplicationInterface
    */
    public function __construct(ApplicationInterface $app)
    {
        $this->setBasePath($app->getBasePath());
    }

    /**    
    * Return base NeuraFrame application path    
    *    
    * @return string    
    */    
    public function getBasePath()    
    {    
        return $this->basePath;    
    }    

    /**    
    * Set a base NeuraFrame application path    
    *    
    * @return string    
    */    
    public function setBasePath($path)    
    {    
        $this->basePath = str_replace(['/','\\'], static::DS,$path);    
    }    

    /**    
    * Determine does the given file exists at give filePath    
    *    
    * @param $filePath     
    * @return bool    
    */    
    public function exists($filePath)    
    {    
        return file_exists($filePath);    
    }    

    /**    
    * Require file from given path    
    *    
    * @param $filePath     
    * @return mixed    
    */    
    public function require($filePath)    
    {    
        if(!$this->exists($this->to($filePath)))    
            throw new FileNotExistsException($this->to($filePath));    

        return require $this->to($filePath);    
    }    

    /**    
    * Return a list of files in specific directory    
    *    
    * @param string $directory    
    * @return array    
    */    
    public function scanDir($directory)    
    {    
        if(!is_dir($directory))    
            throw new InvalidArgumentException("Directory does not exists at path: ".$directory);    

        return array_diff(scandir($directory),array(".",".."));    
    }        

    /**    
    * Generate full path to the given path in vendor folder    
    *    
    * @param string $path     
    * @return string    
    */    
    public function toVendor($path = '')    
    {    
        return $this->to('vendor/'.$path);    
    }    

    /**    
    * Generate full path to the given path in NeuraFrame folder    
    *    
    * @param string $path     
    * @return string    
    */    
    public function toNeuraFrame($path = '')    
    {    
        return $this->toVendor('NeuraFrame/src/'.$path);    
    }    

    /**    
    * Generate full path to App folder    
    *    
    * @param string $path    
    * @return string    
    */    
    public function toApp($path = '')    
    {    
        return $this->to('app/'.$path);    
    }    

    /**    
    * Generate full path to Public directory    
    *    
    * @param string $path     
    * @return string    
    */    
    public function toPublic($path = '')    
    {    
        return $this->to('../public/'.$path);    
    }    

    /**    
    * Generate full path to Uploads directory    
    *    
    * @param string $path     
    * @return string    
    */    
    public function toUploads($path = '')    
    {    
        return $this->toPublic('uploads/');    
    }    

    /**    
    * Check if file have valid extension    
    *    
    * @param string $fileName    
    * @return bool    
    */    
    public function isExtension($fileName,$extension)    
    {    
        return pathinfo($fileName,PATHINFO_EXTENSION) == $extension ? true :false;    
    }    

    /**    
    * Generate full path to the given path    
    *    
    * @param string $path     
    * @return string    
    */    
    public function to($path)    
    {    
        return $this->basePath .static::DS. str_replace(['/','\\'], static::DS,$path);    
    }
}