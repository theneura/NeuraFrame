<?php

namespace NeuraFrame\Database\SqlStatemants;

use NeuraFrame\Database\SqlStatemants\StatemantContainer;
use NeuraFrame\Database\SqlClauses\Table;
use NeuraFrame\Database\SqlClauses\Wheres;
use NeuraFrame\Exceptions\SqlStatemantException;

class UpdateStatemant extends StatemantContainer
{
    /**
    * Constructor
    * 
    * @param array $updatePairs
    * @return void
    */
    public function __construct(array $updatePairs = null)
    {
        $this->clauses = [
            'table'     =>  new Table(),
            'wheres'    =>  new Wheres()
        ];
        
        if(!$updatePairs)
            return;

        $this->columns(array_keys($updatePairs));
        $this->addData(array_values($updatePairs));
    }

    /**    
    * Fetching SQL query string
    *    
    * @throws NeuraFrame\Exceptions\SqlStatemantException
    * @return string    
    */    
    public function getSql()
    {
        if(!$this->hasBindings())
            throw new SqlStatemantException('InsertStatemant','There are no bindings ready for inserting into database');

        $sql = 'UPDATE '. $this->clauses['table']. ' SET ';
        $sql .= $this->getColumnsAndPlaceholders();
        $sql .= $this->clauses['wheres']->getSql();

        return $sql;
    }

    /**
    * Fetching columns and placeholders for updating
    *
    * @return string
    */
    protected function getColumnsAndPlaceholders()
    {
        $sql = '';
        foreach($this->columns as $columnName)
            $sql .= $columnName.' = ? , ';

        $sql = rtrim($sql," , ");
        return $sql;
    }
}