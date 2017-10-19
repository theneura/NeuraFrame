<?php

namespace NeuraFrame\Contracts\Database;

interface SqlClauseInterface
{
    /**
    * Create sql clause and return string
    *
    * @return string
    */
    public function getSql();
}