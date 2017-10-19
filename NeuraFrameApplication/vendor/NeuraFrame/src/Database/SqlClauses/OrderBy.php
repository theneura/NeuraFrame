<?php

namespace NeuraFrame\Database\SqlClauses;
use NeuraFrame\Contracts\Database\SqlClauseInterface;

class OrderBy implements SqlClauseInterface
{

    /**    
    * Container for limit clause    
    *    
    * @var string    
    */    
    protected $orderBy;    

    /**    
    * Create sql clause and return string    
    *    
    * @return string    
    */    
    public function getSql()    
    {    
        if(is_null($this->orderBy))    
            return '';    
        return ' ORDER BY ' .$this->orderBy;    
    }    

    /**    
    * Setting limit    
    *    
    * @var string $limit    
    * @return void    
    */    
    public function set($orderBy)    
    {    
        $this->orderBy = $orderBy;    
    }
}