<?php

namespace NeuraFrame\Contracts\Database;

use NeuraFrame\Contracts\Database\SqlStatemants\StatemantInterface;

interface DatabaseInterface
{
    /**
    * Fetch statemant
    *
    * @param NeuraFrame\Contracts\Database\SqlStatemants\StatemantInterface $statemant
    * @return \StdClass
    */
    public function execute(StatemantInterface $statemant);
}