<?php

namespace NeuraFrame\Support;

use ArrayAccess;

class ArrayContainer implements ArrayAccess
{
    /**
    * Array container
    *
    * @var array
    */
    protected $container;

    /**
    * Array access seting value at offset position
    *
    * @param int $offset
    * @param mixed $value
    * @return void
    */
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**    
    * Array access determine if offset value is set    
    *    
    * @param int $offset    
    * @return bool    
    */    
    public function offsetExists($offset) {    
        return isset($this->container[$offset]);    
    }    

    /**    
    * Array access unsetting offset value    
    *    
    * @param int $offset    
    * @return void    
    */    
    public function offsetUnset($offset) {    
        unset($this->container[$offset]);    
    }    

    /**    
    * Array access get offset value or null    
    *    
    * @param int $offset    
    * @return mixed    
    */    
    public function offsetGet($offset) {    
        return isset($this->container[$offset]) ? $this->container[$offset] : null;    
    }
}