<?php

namespace NeuraFrame\Model;

use NeuraFrame\Model;
use NeuraFrame\Contracts\Application\ApplicationInterface;
use NeuraFrame\Exceptions\ClassNotExistsException;
use NeuraFrame\Exceptions\IsNotSubbclassOfException;

class ModelFactory
{
    /**
    * Models container
    * 
    * @var array
    */
    private $models = [];

    /**
    * Models aliases
    *
    * @var array
    */
    private $aliases = [];

    /**
    * Application Container object
    * 
    * @var \NeuraFrame\ApplicationInterface
    */
    private $app;

    /**
    * Constructor
    *
    * @param \NeuraFrame\ApplicationInterface $app
    */
    public function __construct(ApplicationInterface $app)
    {
        $this->app = $app;
    }

    /**
    * Set model alias
    *
    * @param string $alias 
    * @return void
    */
    public function setAlias($alias)
    {
        $this->aliases[] = $this->getModelName($alias);
    }

    /**
    * Return true if alias exists inside aliases array
    *
    * @param string $alias
    * @return bool
    */
    public function aliasExist($alias)
    {
        return in_array($alias,$this->aliases);
    }

    /**
    * Get the given model
    * 
    * @param string $model 
    * @param bool $addPrefix
    * @return object
    */
    public function model($model)
    {
        if(!$this->hasModel($model) && $this->aliasExist($model))
            $this->addModel($model);
            
        return $this->getModel($model);
    }

    /**
    * Determine if the given class|model exists
    * in the models container
    * 
    * @param string $model 
    * @return bool
    */
    private function hasModel($model)
    {
        return array_key_exists($model,$this->models);
    }

    /**
    * Create new object for the given model class and store it
    * in models container
    * 
    * @param string $modelClass
    * @return void
    */
    public function addModel($modelClass)
    {
        $model = $this->createNewModelObject($modelClass);
        if(!is_subclass_of($model,'\\NeuraFrame\\Model')) 
            throw new IsNotSubbclassOfException(get_class($model),'\\NeuraFrame\\Model');
            
        $this->models[$modelClass] = $model;
    }

    /**
    * Create new model object from given class name
    *
    * @param string $className
    * @throws \NeuraFrame\Exceptions\ClassNotExistsException
    * @return Object[$className]
    */
    private function createNewModelObject($modelClass)
    {
        if(!class_exists($modelClass))
            throw new ClassNotExistsException($modelClass);
        return new $modelClass();
    }
    /**
    * Get the full class name for the given model
    * 
    * @param string $model
    * @return string
    */
    private function getModelName($model)
    {
        
        $model = 'App\\Models\\' . $model;
        return str_replace('/','\\',$model);
    }

    /**
    * Get the model object
    * 
    * @param string $model 
    * @return object
    */
    public function getModel($model)
    {
        return $this->models[$model];
    }    
}