<?php

namespace NeuraFrame\Database\SqlClauses;

use NeuraFrame\Contracts\Database\SqlClauseInterface;
use NeuraFrame\Exceptions\SqlQueryException;

class Table implements SqlClauseInterface
{

    /**    
    * Container for limit clause    
    *    
    * @var string    
    */    
    protected $table;    

    /**    
    * Create sql clause and return string    
    *    
    * @return string    
    */    
    public function getSql()    
    {    
        if(is_null($this->table))    
            throw new SqlQueryException('Fail while constructing sqlQuery, there is no selected table!');
        return ' FROM '.$this->table;   
    }   

    /**    
    * Create sql clause and return string    
    *    
    * @return string    
    */    
    public function __toString()    
    {    
        if(is_null($this->table))    
            throw new SqlQueryException('Fail while constructing sqlQuery, there is no selected table!');
        return $this->table;   
    } 

    /**    
    * Setting limit    
    *    
    * @var string $limit    
    * @return void    
    */    
    public function set($table)    
    {    
        $this->table = $table;    
    }
}