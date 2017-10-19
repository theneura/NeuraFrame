<?php

namespace NeuraFrame\Database;

use NeuraFrame\Database\SqlStatemants\InsertStatemant;
use NeuraFrame\Database\SqlStatemants\UpdateStatemant;
use NeuraFrame\Database\SqlStatemants\DeleteStatemant;
use NeuraFrame\Database\SqlStatemants\SelectStatemant;

class QueryBuilder 
{
    /**
    * Creating and returning Select Sql statemant
    *
    * @param string $select
    * @return NeuraFrame\Database\SqlStatemants\SelectStatemant
    */
    public function select($select = '*')
    {
        return new SelectStatemant($select);
    }

    /**
    * Creating and returning Update Sql statemant
    *
    * @param array $updatePairs
    * @return NeuraFrame\Database\SqlStatemants\UpdateStatemant
    */
    public function update(array $updatePairs = null)
    {
        return new UpdateStatemant($updatePairs);
    }

    /**
    * Creating and returning Insert Sql statemant
    *
    * @param array $insertPairs
    * @return NeuraFrame\Database\SqlStatemants\InsertStatemant
    */
    public function insert(array $insertPairs = null)
    {
        return new InsertStatemant($insertPairs);
    }

    /**
    * Creating and returning Insert Sql statemant
    *
    * @return NeuraFrame\Database\SqlStatemants\DeleteStatemant
    */
    public function delete()
    {
        return new DeleteStatemant();
    }
}