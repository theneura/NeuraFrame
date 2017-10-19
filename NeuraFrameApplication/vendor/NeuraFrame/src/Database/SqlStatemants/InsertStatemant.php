<?php

namespace NeuraFrame\Database\SqlStatemants;

use NeuraFrame\Database\SqlStatemants\StatemantContainer;
use NeuraFrame\Database\SqlClauses\Table;
use NeuraFrame\Exceptions\SqlStatemantException;

class InsertStatemant extends StatemantContainer
{
    /**
    * Constructor
    * 
    * @return void
    */
    public function __construct(array $updatePairs = null)
    {
        $this->clauses = [
            'table'     =>  new Table()
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

        $sql = 'INSERT INTO '. $this->clauses['table'];
        $sql .= $this->getColumns();
        $sql .= $this->getPlaceholders();

        return $sql;
    }

    /**
    * Fetching columns for updating or saving
    *
    * @return string
    */
    protected function getColumns()
    {
        return ' ('. implode(', ',$this->columns).')';
    }

    /**
    * Fetching columns for updating or saving
    *
    * @return string
    */
    protected function getPlaceholders()
    {
        return ' VALUES ('.str_repeat('?, ',sizeof($this->columns) - 1).'?)';
    }
}