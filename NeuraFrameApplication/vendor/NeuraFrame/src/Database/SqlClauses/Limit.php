<?php

namespace NeuraFrame\Database\SqlClauses;
use NeuraFrame\Contracts\Database\SqlClauseInterface;

class Limit implements SqlClauseInterface
{

    /**    
    * Container for limit clause    
    *    
    * @var string    
    */    
    protected $limit;    

    /**    
    * Create sql clause and return string    
    *    
    * @return string    
    */    
    public function getSql()    
    {    
        if(is_null($this->limit))    
            return '';    
        return ' LIMIT '.$this->limit;  
    }    

    /**    
    * Setting limit    
    *    
    * @var string $limit    
    * @return void    
    */    
    public function set($limit)    
    {    
        $this->limit = $limit;    
    }
}