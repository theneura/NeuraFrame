<?php

namespace NeuraFrame\Database\SqlStatemants;

use NeuraFrame\Database\SqlStatemants\StatemantContainer;
use NeuraFrame\Exceptions\SqlStatemantException;
use NeuraFrame\Database\SqlClauses\Table;
use NeuraFrame\Database\SqlClauses\Wheres;

class DeleteStatemant extends StatemantContainer
{
    /**
    * Constructor
    * 
    * @return void
    */
    public function __construct()
    {
        $this->clauses = [
            'table'     =>  new Table(),
            'wheres'    =>  new Wheres()
        ];
    }

    /**    
    * Fetching SQL query string
    *    
    * @throws NeuraFrame\Exceptions\SqlStatemantException
    * @return string    
    */    
    public function getSql()
    {
        $sql = 'DELETE FROM '. $this->clauses['table'];
        $sql .= $this->clauses['wheres']->getSql();
        
        return $sql;
    }
}