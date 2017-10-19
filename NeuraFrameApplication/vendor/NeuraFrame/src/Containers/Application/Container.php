<?php

namespace NeuraFrame\Containers\Application;

use NeuraFrame\Exceptions\ClassNotExistsException;
use ReflectionClass;
use OutOfRangeException;
use InvalidArgumentException;


class Container
{    
    /**    
    * Container for bindings     
    *    
    * @var array    
    */    
    protected $bindings = array();     

    /**    
    * Container for singleton instances    
    *    
    * @var array    
    */    
    protected $instances = array();    

    /**    
    * Container for singleton booleans  
    *    
    * @var array    
    */    
    protected $singletons = array();   

    /**    
    * Container for interfaces  
    *    
    * @var array    
    */ 
    protected $abstracts = array();  

    /**    
    * Bind new value to bindings container, also save bool for singletone to given key    
    *    
    * @param string $key    
    * @param string $value    
    * @param array  $abstracts    
    * @return void    
    */    
    public function bind($key,$value,$singleton = false,$abstracts = array())    
    {                
        $this->bindings[$key] = $value;
        $this->singletons[$key] = $singleton;

        foreach($abstracts as $abstract)
            $this->abstracts[$abstract] = $key;  
    }  

    /**    
    * Create new binding but set true as singleton key    
    *    
    * @param string $key    
    * @param string $value    
    * @param array $abstracts
    * @return void    
    */    
    public function singleton($key,$value,$abstracts = array())    
    {
        $this->bind($key,$value,true,$abstracts);    
    }   

    /**
    * Force singleton, pass object as parameter
    *
    * @param string $key
    * @param string $object
    * @return void
    */
    public function forceSingleton($key,$object)
    {
        $interfaces = class_implements($object);
        $class = get_class($object);

        $this->singleton($key,$class,$interfaces);
        $this->instances[$key] = $object;
    }      

    /**    
    * Register core singletone instances    
    *    
    * @param array $classes    
    * @return void    
    */    
    protected function registerCoreClasses(array $classes = array())    
    {
        foreach($classes as $classKey => $classValue)    
            $this->bind($classKey,$classValue['className'],$classValue['singleton'],$classValue['abstracts']);    
    }      

    /**    
    * Get binding by key if exists, if not throw Exception    
    *    
    * @param string $key    
    * @throws \InvalidArgumentException    
    * @return string    
    */    
    protected function getBinding($key)    
    {    
        if(!array_key_exists($key,$this->bindings))    
            throw new OutOfRangeException('Cannot find binding by key: '.$key);    

        return $this->bindings[$key];    
    }    

    /**    
    * Check if the given key is registered as singleton    
    *    
    * @param sring $key    
    * @return bool    
    */    
    protected function isSingleton($key)    
    {    
        return $this->singletons[$key];    
    }    

    /**    
    * Returning a singleton instance    
    *    
    * @param sring $key    
    * @return mixed    
    */    
    protected function getSingletonInstance($key)    
    {    
        return $this->instances[$key];    
    }    

    /**    
    * Check if the given key exists in bindings array    
    *    
    * @param string $key    
    * @return bool    
    */    
    protected function isBindingExists($key)    
    {    
        return array_key_exists($key,$this->bindings);    
    }    

    /**    
    * Check if the key is resolved as singleton    
    *    
    * @param string $key    
    * @return bool    
    */    
    protected function isSingletonResolved($key)    
    {    
        return array_key_exists($key,$this->instances);    
    } 

    /**    
    * Check if abstract class exists
    *    
    * @param string $key    
    * @return bool    
    */    
    protected function isAbstractExist($className)    
    {    
        return array_key_exists($className,$this->abstracts);    
    }     

    /**    
    * Resolve name with class name    
    *    
    * @param string $className    
    * @return string    
    */    
    protected function resolveName($className)    
    {    
        if(!$this->isBindingExists($className) && !$this->isAbstractExist($className))    
            $this->bind($className,$className);    

        return $this->resolve($className);    
    }    

    /**    
    * Resolve binding by given key and arguments    
    *    
    * @param string $key    
    * @param array $args    
    * @return mixed    
    */    
    public function resolve($key,array $args = array())    
    {    
        $key = $this->parseKey($key);    
        $class = $this->getClassByKey($key);    
            
        if($this->isSingleton($key) && $this->isSingletonResolved($key))    
            return $this->getSingletonInstance($key);    

        $object =  $this->createInstance($class,$args);    

        if($this->isSingleton($key))    
            $this->instances[$key] = $object;    
            
        return $object;    
    }    

    /**
    * Return parent class to abstract class
    *
    * @param string $key
    * @return string
    */
    public function getAbstract($key)
    {
        return $this->abstracts[$key];
    }

    /**    
    * Check if key is available for abstract classes    
    *    
    * @param string $key    
    * @return void    
    */    
    protected function parseKey($key)    
    {    
        if($this->isAbstractExist($key))    
            return $this->getAbstract($key);    

        return $key;    
    }    

    /**    
    * Return class by given key    
    *    
    * @param string $key    
    * @return array    
    */    
    protected function getClassByKey($key)    
    {    
        if($this->isBindingExists($key))    
            return $this->getBinding($key);     

        return $key;    
    }    

    /**    
    * Create new object by given class and passed args    
    *    
    * @param string $className    
    * @param array $args    
    * @throws \InvalidArgumentException    
    * @return mixed    
    */    
    protected function createInstance($className,array $args = array())    
    {            
        if(!class_exists($className) && !interface_exists($className))    
            throw new InvalidArgumentException('Given class: '.$className.' does not exists!');            

        $reflector = $this->createReflection($className);            
        $this->resolvingArguments($reflector,$args);  

        $object = $reflector->newInstanceArgs($args);
        return $object;    
    }    

    /**    
    * Resolving arguments for dependecies injection    
    *     
    * @param ReflectionClass $reflector     
    * @param array &$args    
    * @return void    
    */    
    protected function resolvingArguments(ReflectionClass $reflector,&$args = null)    
    {    
        if(is_null($reflector->getConstructor()))    
            return null;    

        $constructor = $reflector->getConstructor();    
        $dependencis = $constructor->getParameters();    
        $args = $this->buildDependecies($args,$dependencis);    
    }

    /**    
    * Generate and return Reflection class by passing className    
    *    
    * @param string $className    
    * @throws \InvalidArgumentException    
    * @return ReflactionClass    
    */    
    protected function createReflection($className)    
    {    
        $reflector = new ReflectionClass($className);  

        if(!$reflector->isInstantiable())    
            throw new \InvalidArgumentException('Class '.$className.' is not istantiable');    

        return $reflector;    
    }     
    
    /**    
    * Build dependecies for creating new object    
    *    
    * @param array $args    
    * @param array $dependencis    
    * @return array    
    */    
    protected function buildDependecies($args,$dependencis)    
    {    
        foreach(array_reverse($dependencis) as $dependency)    
        {    
            if($dependency->isOptional()) continue;    
            if($dependency->isArray()) continue;   

            $class = $dependency->getClass();     

            if($class === null) continue;       

            if(get_class($this) === $class->name){    
                array_unshift($args,$this);    
                continue;    
            }    
            array_unshift($args,$this->resolveName($class->name));                
        }    
        return $args;    
    }   
}