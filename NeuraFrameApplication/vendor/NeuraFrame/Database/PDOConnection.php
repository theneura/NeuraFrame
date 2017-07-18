<?php

namespace NeuraFrame\Database;

use PDO;
use PDOException;
use RuntimeException;

abstract class PDOConnection
{
    /**
    * PDO Connection
    *
    * @var \PDO
    */
    public static $connection;


    /**
    * Connect to database
    *
    * @param array $dbConnectionData
    * @return void
    */
    protected function connect($dbConfig)
    {
        try{
            static::$connection = new PDO(  'mysql:host='.$dbConfig['host'].';'. 'dbname='.$dbConfig['name'],$dbConfig['username'],$dbConfig['password']);
            static::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
            static::$connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            static::$connection->exec('SET NAMES '.$dbConfig['charset']);
        }catch(PDOException $e)
        {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
    * Get datatbase Connection object PDO object
    *
    * @return \PDO
    */
    protected function connection()
    {
        return static::$connection;
    }

    /**
    * Determine if there is any connection to database
    *
    * @return bool
    */
    protected function isConnected()
    {
        return static::$connection instanceof PDO;
    }
}