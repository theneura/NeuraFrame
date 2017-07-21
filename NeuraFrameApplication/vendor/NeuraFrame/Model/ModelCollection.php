<?php

namespace NeuraFrame\Model;

class ModelCollection
{
    /**
    * Collection of stdClass objects
    * 
    * @var array
    */
    private $collection = [];

    /**
    * Constructor 
    *
    * @param array[\stdClass] $collection
    */
    public function __construct($collection = [])
    {
        $this->collection = $collection;
    }

    /**
    * Convert stdClass objects to model class and return
    *
    * @param string $modelClassName
    * @return array
    */
    public function toArray($modelClassName)
    {
        if(sizeof($this->collection) == 0)
            return null;
            
        $models = [];
        foreach($this->collection as $stdObject)
        {
            $models[] = $this->stdToModel($modelClassName,$stdObject);
        }      
        return $models;
    }

    /**
    * Cast stdClass object to Model objecy
    *
    * @param string $modelClassName
    * @return mixed
    */
    public function toModel($modelClassName)
    {
        if(!isset($this->collection))
            return null;
        return $this->stdToModel($modelClassName,$this->collection);
    }

    /**
    * Cast stdClass object to Model object
    *
    * @param string $modelClassName
    * @param /StdObject $stdClass
    * @return mixed
    */
    private function stdToModel($modelClassName,\stdClass $object = null)
    {
        if(!$object)
            return null;
        $newModel = new $modelClassName;
        $newModel->setData((array)$object);
        return $newModel;
    }    
}