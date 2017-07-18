<?php

namespace NeuraFrame\Database;

use NeuraFrame\Contracts\Application\ApplicationInterface;

class Database extends PDOConnection
{
    use DatabaseQuery;

    /**
    * Application Container object
    *
    * @var \NeuraFrame\ApplicationInterface
    */
    private $app;        

    /**
    * Constructor
    *
    * @param \NeuraFrame\ApplicationInterface
    */
    public function __construct(ApplicationInterface $app)
    {
        $this->app = $app;
        if(!$this->isConnected())
            $this->connect($this->app->config['database']);
    }      
}