<?php

namespace NeuraFrame\Database\SqlClauses;
use NeuraFrame\Contracts\Database\SqlClauseInterface;

class Offset implements SqlClauseInterface
{

    /**    
    * Container for limit clause    
    *    
    * @var string    
    */    
    protected $offset;    

    /**    
    * Create sql clause and return string    
    *    
    * @return string    
    */    
    public function getSql()    
    {    
        if(is_null($this->offset))    
            return '';    
        return ' OFFSET '.$this->offset;   
    }    

    /**    
    * Setting limit    
    *    
    * @var string $limit    
    * @return void    
    */    
    public function set($offset)    
    {    
        $this->offset = $offset;    
    }
}