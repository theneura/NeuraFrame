<?php

namespace NeuraFrame\Database\SqlClauses;

use NeuraFrame\Support\ArrayContainer;
use NeuraFrame\Contracts\Database\SqlClauseInterface;

use ArrayAccess;

class Wheres extends ArrayContainer implements SqlClauseInterface
{

    /**
    * Create sql clause and return string
    *
    * @return string
    */
    public function getSql()
    {
        if(empty($this->container))
            return '';
        return ' WHERE '.implode(' AND ',$this->container);
    }
}