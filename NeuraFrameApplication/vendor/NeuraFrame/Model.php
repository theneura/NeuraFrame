<?php

namespace NeuraFrame;

use NeuraFrame\Application;
use NeuraFrame\Model\ModelQuery;
use NeuraFrame\Contracts\Model\DatabaseTable;
use RuntimeException;

abstract class Model implements DatabaseTable
{
    /**
    * Table name
    *
    * @var string
    */
    protected $table;

    /**
    * Variable container 
    *
    * @var array
    */
    protected $container;

    /**
    * Constructor
    *
    */
    public function __construct()
    {
        if(!isset($this->table))
            $this->setTable($this->generateTableName());
    }    
    
    /**
    * Generate name for database table
    *
    * @return string
    */
    private function generateTableName()
    {
        $tableName = explode('\\',strtolower(get_class($this)."s"));
        return end($tableName);
    }    

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
        if(self::haveKey($key))
        {
            $this->container[$key] = $value;
            return;
        }
        throw new RuntimeException($key." does not exist inside database table ".$this->getTable());
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
    * Get all data saved inside model
    *
    * @param array $data
    */
    public function setData($data)
    {
        $this->container = $data;
    }

    /**
    * Set new table name
    *
    * @param string $table
    * @return void
    */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
    * Return table name
    *
    * @return string
    */
    public function getTable()
    {
        return $this->table;
    }

    /**
    * Update model data to database
    *
    * @return \NeuraFrame\Database\DatabaseQueryInterface
    */
    public function update()
    {
        return self::updateData($this->getData());
    }

    /**
    * Save new data to database
    *
    * @return \NeuraFrame\Database\DatabaseQueryInterface
    */
    public function save()
    {
        return self::saveNewData($this->getData());
    }

    /**
    * Delete model from database
    *
    * @return \NeuraFrame\Database\DatabaseQueryInterface
    */
    public function delete()
    {
        return self::deleteData($this->id);
    }

    /**
    * Call static where function from nonStatic
    *
    */
    public function where($where)
    {
        return self::whereFromNonStatic($where);
    }

    /**
    * * Call static limit function from nonStatic
    *
    */
    public static function limit($limit)
    {
        return self::limitFromNonStatic($limit);
    }

    /**
    * * Call static orderBy function from nonStatic
    *
    */
    public static function orderBy($row,$orderBy ="ASC")
    {
        return self::orderByFromNonStatic($row,$orderBy);
    }


    /**
    * Get data from query
    *
    * @return \NeuraFrame\Database\DatabaseQueryInterface
    */
    public function get()
    {
        return self::all();
    }

    /**
    * Call dynamically static function for querying database
    *
    * @param string $name 
    * @param array $arguments
    * @return mixed
    */
    public static function __callStatic($name, $arguments=[])
    {
        $app = Application::getInstance();
        $model = $app->modelFactory->model(get_called_class());
        $functionName = "NeuraFrame\Model\ModelQueryBuilder::".$name;
        return call_user_func_array($functionName,array_merge(array($app,$model),$arguments));
    }
}