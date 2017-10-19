<?php

namespace NeuraFrame\Contracts\Support\Configuration;

interface ConfigDatabaseInterface
{
    /**
    * Get configurations for database connection
    *
    * @return array
    */
    public function getDatabaseConfig();
}