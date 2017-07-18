<?php

namespace NeuraFrame\FileSystem;

use NeuraFrame\Exceptions\FileNotExistsException;
use NeuraFrame\Contracts\Application\ApplicationInterface;


class File
{
    /**
    * Application object container
    *
    * @var NeuraFrame\ApplicationInterface
    */
    private $app;

    /**
    * Directory Separator
    *
    * @const string
    */
    const DS = DIRECTORY_SEPARATOR;

    /**
    * Base path for application
    *
    * @var string
    */
    private $basePath;   

    /**
    * Constructor
    *
    * @param NeuraFrame\ApplicationInterface $app
    */
    public function __construct(ApplicationInterface $app)
    {
        $this->app = $app;
        $this->basePath = $app->getBasePath();
    }

    /**
    * Determine wether the given file path exists
    *
    * @param $file 
    * @return boo
    */
    public function exists($file)
    {
        return file_exists($this->to($file));
    }

    /**
    * Require the given file
    *
    * @param $file 
    * @return mixed
    */
    public function call($file)
    {
        if(!file_exists($this->to($file)))
            throw new FileNotExistsException($this->to($file));
        return require $this->to($file);
    }

    /**
    * Generate full path to the given path in vendor folder
    *
    * @param string $path 
    * @return string
    */
    public function toVendor($path)
    {
        return $this->to('vendor/'.$path);
    }

    /**
    * Generate full path to App folder
    *
    * @param string $path
    * @return string
    */
    public function toApp($path)
    {
        return $this->to('App/'.$path);
    }

    /**
    * Generate full path to the given path
    *
    * @param string $path 
    * @return string
    */
    public function to($path)
    {
        return $this->basePath . str_replace(['/','\\'], static::DS,$path);
    }

    /**
    * Return a list of files in specific directory
    *
    * @param string $directory
    * @return array
    */
    public function scanDir($directory)
    {
        return array_diff(scandir($directory),array(".",".."));
    }

    /**
    * Check if file is with valid extension
    *
    * @param string $fileName
    * @return bool
    */
    public function isExtension($fileName,$extension)
    {
        return pathinfo($fileName,PATHINFO_EXTENSION) == $extension ? true :false;
    }

    /**
    * Returns filename without extension
    *
    * @param string $baseName
    * @return string
    */
    public function getFileName($baseName)
    {
        return pathinfo($baseName,PATHINFO_FILENAME);
    }
}