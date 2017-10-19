<?php

namespace NeuraFrame\Contracts\Database;

interface PDOInterface
{
    /**
    * Get datatbase Connection object PDO object
    *
    * @return \PDO
    */
    public function connection();
}