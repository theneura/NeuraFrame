<?php

namespace NeuraFrame\Orm;

use NeuraFrame\Contracts\Database\DatabaseInterface;
use NeuraFrame\Contracts\Orm\MapperModelFactoryInterface;
use NeuraFrame\Exceptions\ClassNotExistsException;
use ReflectionClass;

class MapperModelFactory implements MapperModelFactoryInterface
{
    /**
    * Interface to database object, used for executing SQL queries
    *
    * @var NeuraFrame\Contracts\Database\DatabaseInterface
    */
    private $dbInterface;

    /**
    * Array of mapper models
    *
    * @var array
    */
    private $mapperModels = array();

    /**
    * Constructor
    *
    * @param NeuraFrame\Contracts\Database\DatabaseInterface $dbInterface
    */
    public function __construct(DatabaseInterface $dbInterface)
    {
        $this->dbInterface = $dbInterface;
    }

    /**
    * Return instnace of mapper class
    *
    * @param string $mapperClassAlias
    * @return mixed
    */
    public function getMapper($mapperClassAlias)
    {
        if(!$this->hasMapperModel($mapperClassAlias))
            $this->addNewMapperModel($mapperClassAlias);

        return $this->getMapperModel($mapperClassAlias);
    }

    /**
    * Determine if given mapperClassAlias exist as key value in mappedModels array
    *
    * @param string $mapperClassAlias
    * @return bool
    */
    private function hasMapperModel($mapperClassAlias)
    {
        return array_key_exists($mapperClassAlias,$this->mapperModels);
    }

    /**
    * Creating and adding new mapper model to mappedModels array
    *
    * @param string $mapperClassAlias
    * @throws \InvalidArgumentException
    * @return void
    */
    private function addNewMapperModel($mapperClassAlias)
    {
        if(!class_exists($mapperClassAlias))
            throw new ClassNotExistsException('Invalid mapper class name: '.$mapperClassAlias.'. Required class does not exist!');
        
        $this->mapperModels[$mapperClassAlias] =  $this->createInstanceOfMapperModel($mapperClassAlias);
    }

    /**
    * Creating instance of mapped model class, and check does passed class alias, extends base class
    *
    * @param string $mapperClassAlias
    * @throws \LogicException
    * @return mixed
    */
    private function createInstanceOfMapperModel($mapperClassAlias)
    {
        $reflection = $this->createReflectionObject($mapperClassAlias);
        
        if(!$reflection->isSubclassOf('NeuraFrame\\Orm\\Mapper'))
            throw new \LogicException('Required mapper class: '.$mapperClassAlias.' must extend base class: NeuraFrame\\Orm\\Mapper');

        return $reflection->newInstanceArgs(array($this->dbInterface));
    }

    /**
    * Creating reflection object of passed mappedClassAlias, and check if that class is instantiable
    *
    * @param string $mapperClassAlias
    * @throws \LogicException
    * @return \ReflectionClass
    */
    private function createReflectionObject($mapperClassAlias)
    {
        $reflector = new ReflectionClass($mapperClassAlias);  

        if(!$reflector->isInstantiable())    
            throw new \LogicException('Failed to create mapper model object, given class '.$mapperClassAlias.' is not istantiable');    

        return $reflector;  
    }

    /**
    * Return instance of mapperClassAlias from array
    *
    * @param string $mapperClassAlias
    * @return mixed
    */
    private function getMapperModel($mapperClassAlias)
    {
        return $this->mapperModels[$mapperClassAlias];
    }
}