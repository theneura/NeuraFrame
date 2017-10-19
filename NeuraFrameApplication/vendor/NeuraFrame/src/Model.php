<?php

namespace NeuraFrame;

abstract class Model 
{
    /**
    * Variable container 
    *
    * @var array
    */
    protected $container;

    /**
    * Get variable from container
    *
    * @param string $key
    * @return mixed
    */
    public function __get($key)
    {
        return $this->container[$key];
    }

    /**
    * Check if variable with given key exists, this its require for twig to get values from container
    *
    * @param string $key
    * @return bool
    */
    public function __isset($key)
    {
        return array_key_exists($key,$this->container);
    }

    /**
    * Set variable to container
    *
    * @param string $key 
    * @param string $value 
    * @return void
    */
    public function __set($key,$value)
    {
        $this->container[$key] = $value;        
    }

    /**
    * Get all data saved inside model
    *
    * @return array
    */
    public function getData()
    {
        return $this->container;
    }

    /**
    * Save all data inside model
    *
    * @param array $data
    */
    public function setData($data)
    {
        $this->container = $data;
    }

    /**
    * Calling dynamically static function that are not defined
    *
    * @param string $name 
    * @param array $arguments
    * @return mixed
    */
    public static function __callStatic($name, $arguments=[])
    {
        throw new \BadMethodCallException('Required Static method: '.$name.' is not defined inside model class');
    }

    /**
    * Calling dynamically static function that are not defined
    *
    * @param string $name 
    * @param array $arguments
    * @return mixed
    */
    public function __call($name, $arguments=[])
    {
        throw new \BadMethodCallException('Required method: '.$name.' is not defined inside model class: '.get_class($this));
    }
}