<?php

namespace NeuraFrame\Database\SqlStatemants;

use NeuraFrame\Database\SqlStatemants\StatemantContainer;
use NeuraFrame\Database\SqlClauses\Selects;
use NeuraFrame\Database\SqlClauses\Table;
use NeuraFrame\Database\SqlClauses\Joins;
use NeuraFrame\Database\SqlClauses\Wheres;
use NeuraFrame\Database\SqlClauses\Offset;
use NeuraFrame\Database\SqlClauses\OrderBy;
use NeuraFrame\Database\SqlClauses\Limit;

class SelectStatemant extends StatemantContainer
{
    /**
    * Constructor
    * 
    * @param string $select
    * @return void
    */
    public function __construct($select = null)
    {
        $this->clauses = [
            'selects'   =>  new Selects(),
            'table'     =>  new Table(),
            'joins'     =>  new Joins(),
            'wheres'    =>  new Wheres(),            
            'orderBy'   =>  new OrderBy(),
            'limit'     =>  new Limit(),
            'offset'    =>  new Offset()
        ];

        if($select)
            $this->select($select);
    }

    /**    
    * Set select clause    
    *    
    * @param string $select     
    * @return this    
    */    
    public function select($select)    
    {    
        $this->clauses['selects'][] = $select;    
        return $this;    
    }

    /**    
    * OrderBy clause   
    *    
    * @param string $orderBy     
    * @return $this     
    */    
    public function orderBy($orderByKey,$orderByValue = 'ASC')
    {    
        $this->clauses['orderBy']->set($orderByKey .' '.$orderByValue);
        return $this;
    } 

    /**    
    * Set Limit and offset    
    *    
    * @param int $limit     
    * @param int $offset    
    * @return this    
    */    
    public function limit($limit,$offset = 0)    
    {    
        $this->clauses['limit']->set($limit);    
        $this->clauses['offset']->set($offset);    
        return $this;    
    }

    /**    
    * Fetching SQL query string
    *    
    * @return string    
    */    
    public function getSql()
    {
        $sql = 'SELECT ';    

        foreach($this->clauses as $clause)
            $sql .= $clause->getSql();

        return $sql;  
    }
}