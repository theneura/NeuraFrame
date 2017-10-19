<?php

namespace NeuraFrame\Database;

use NeuraFrame\Exceptions\SqlQueryException;
use NeuraFrame\Contracts\Database\PDOInterface;
use NeuraFrame\Contracts\Database\SqlStatemants\StatemantInterface;
use NeuraFrame\Contracts\Database\DatabaseInterface;


class Database implements DatabaseInterface
{
    /**
    * PDO interface object
    *
    * @var \NeuraFrame\Contracts\Database\PDOInterface
    */
    protected $pdo;

    /**
    * Constructor
    *
    * @param \NeuraFrame\Contracts\Database\PDOInterface
    * @return void
    */
    public function __construct(PDOInterface $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
    * Fetch statemant
    *
    * @param NeuraFrame\Contracts\Database\SqlStatemants\StatemantInterface $statemant
    * @return \StdClass
    */
    public function execute(StatemantInterface $statemant)
    {
        $result = $this->query($statemant);
        return $result ? $result : null;
    }

    /**
    * Query and return resoult from database
    *
    * @param NeuraFrame\Contracts\Database\SqlStatemants\StatemantInterface $statemant
    * @return \StdClass
    */
    private function query(StatemantInterface $statemant)
    {
        try{
            $query = $this->pdo->connection()->prepare($statemant->getSql());

            if(!$statemant->hasBindings())
                $query->execute();
            
            foreach($statemant->getBindings() as $binding)
                $this->executeBinding($query,$binding);
            
            return $query;
        }catch(PDOException $e)
        {
            throw new SqlQueryException('Failing to execute Sql query: '.$queryBuilder->fetchSql().' .Error message: '.$e->getMessage());
        }
    }

    /**
    * Execute each binding
    *
    * @param \PDOStatemant $query
    * @param array $binding
    */
    private function executeBinding($query,$binding)
    {
        $this->bindValues($query,$binding); 
        $query->execute();   
    }

    /**
    * Bind values before query execution
    *
    * @param \PDOStatemant $query
    * @param array $bindings
    */
    private function bindValues($query,$bindings)
    {
        foreach($bindings as $bindingKey => $bindingValue)
                $query->bindValue(
                    is_string($bindingKey) ? $bindingKey : $bindingKey + 1,
                    $bindingValue,
                    is_int($bindingValue) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
        
    }
}