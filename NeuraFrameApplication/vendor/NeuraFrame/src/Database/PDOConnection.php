<?php


namespace NeuraFrame\Database;

use PDO;
use PDOException;
use InvalidArgumentException;
use NeuraFrame\Exceptions\DatabaseConnectionException;
use NeuraFrame\Contracts\Database\PDOInterface;
use NeuraFrame\Contracts\Support\Configuration\ConfigDatabaseInterface;

class PDOConnection implements PDOInterface
{

    /**
    * PDO connection
    * @var PDO;
    */
    protected $connection;

    /**
    * Configuration for PDOConnection
    *
    * @var array
    */
    protected $config;

    /**
    * Constructor
    *
    * @return void
    */
    public function __construct(ConfigDatabaseInterface $dbConfig)
    {
        $this->config = $dbConfig->getDatabaseConfig();
    }

    /**
    * Creating new connection
    *
    * @return void
    */
    private function connect()
    {
        try{
            $this->connection = new PDO(  'mysql:host='.$this->config['host'].';'. 'dbname='.$this->config['name'],$this->config['username'],$this->config['password']);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $this->connection->exec('SET NAMES '.$this->config['charset']);
        }catch(PDOException $e)
        {
            throw new DatabaseConnectionException($e->getMessage());
        }
    }

    /**
    * Get datatbase Connection object PDO object
    *
    * @return \PDO
    */
    public function connection()
    {
        if(!$this->isConnected())
            $this->connect();

        return $this->connection;
    }

    /**
    * Determine if there is any connection to database
    *
    * @return bool
    */
    protected function isConnected()
    {
        return $this->connection instanceof PDO;
    }
}