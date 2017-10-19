<?php

namespace NeuraFrame\Database\SqlStatemants;

use NeuraFrame\Contracts\Database\SqlStatemants\StatemantInterface;

abstract class StatemantContainer implements StatemantInterface
{
    /**
    * Clauses array
    *
    * @var array
    */
    protected $clauses;

    /**
    * Binding values
    *
    * @var array
    */
    protected $columns = array();

    /**
    * Binding values
    *
    * @var array
    */
    protected $bindings = array();

    /**    
    * Set the table name    
    *    
    * @param string $table     
    * @return $this     
    */    
    public function table($table = null)    
    {    
        if($table)    
            $this->clauses['table']->set($table);    
        return $this;    
    }    

    /**    
    * Add new where clause    
    *    
    * @param string $where    
    * @return $this     
    */    
    public function where($where)    
    {    
        $this->clauses['wheres'][] = $where;
        return $this;    
    } 

    /**    
    * Select database table   
    *    
    * @param string $table     
    * @return $this     
    */    
    public function from($table)    
    {    
        return $this->table($table);    
    } 

    /**    
    * Select database table   
    *    
    * @param string $table     
    * @return $this     
    */    
    public function into($table)    
    {    
        return $this->table($table);    
    } 

    /**
    * Determine if there are binded parameters
    * 
    * @return bool
    */
    public function hasBindings()
    {
        return !empty($this->bindings);
    }

    /**
    * Set columns for updating or saving
    *
    * @param array $columns
    */
    public function columns(array $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    /**
    * Adding data for updating or saving 
    *
    * @param array $newData 
    */
    public function addData(array $newData)
    {        
        $this->bindings[] = $newData;
        return $this;
    } 

    /**
    * Return bindings array
    *
    * @return array
    */
    public function getBindings()
    {
        return $this->hasBindings() ? $this->bindings : array();
    }

    /**    
    * Fetching SQL query string
    *    
    * @return string    
    */    
    public abstract function getSql();
}