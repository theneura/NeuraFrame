<?php

namespace NeuraFrame\FileSystem;

use NeuraFrame\Exceptions\FileNotExistsException;
use NeuraFrame\Exceptions\FileFailUploadingException;
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
    * @param $filePath 
    * @return boo
    */
    public function exists($filePath)
    {
        return file_exists($filePath);
    }

    /**
    * Require the given file
    *
    * @param $file 
    * @return mixed
    */
    public function call($file)
    {
        if(!$this->exists($this->to($file)))
            throw new FileNotExistsException($this->to($file));
        return require $this->to($file);
    }

    /**
    * Creating new file and store it to given location
    *
    * @param array $data 
    * @param string $path 
    * @throws \NeuraFrame\Exceptions\FileFailUploadingException
    * @return string
    */
    public function createNewFile($data,$path = '')
    {
        $fileExtension = $this->extension($data['name']);

        if($data['error'] != 0 && $data['size'] > 2097152)
            throw new FileFailUploadingException(['error' => $data['error'],'size' => $data['size']]);
        
        $fileNewName = uniqid('',true).'.'.$fileExtension;
        $fileDestination = $this->toUploads($path.$fileNewName);

        if(!move_uploaded_file($data['tmp_name'],$fileDestination))
           throw new FileFailUploadingException(['error' => $data['error'],'size' => $data['size'],'tmp_name' => $data['tmp_name'],'fileDestination' => $fileDestination]); 

        return 'uploads/'.$path.$fileNewName;
    }

    /**
    * Deleting file if exits in uploads folder
    *
    * @param string $filePath
    * @return bool
    */
    public function deleteFileIfExists($filePath)
    {
        $path = $this->toPublic($filePath);
        if($this->exists($path))
            return unlink($path);
        return false;
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
    * Generate full path to Public directory
    *
    * @param string $path 
    * @return string
    */
    public function toPublic($path)
    {
        return $this->to('../public/'.$path);
    }

    /**
    * Generate full path to Uploads directory
    *
    * @param string $path 
    * @return string
    */
    public function toUploads($path)
    {
        return $this->to('../public/uploads/'.$path);
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
    * Get extension from given file path
    *
    * @param string $filePath
    * @return string
    */
    public function extension($filePath)
    {
        return pathinfo($filePath,PATHINFO_EXTENSION);
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