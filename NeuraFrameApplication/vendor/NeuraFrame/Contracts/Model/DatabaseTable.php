<?php

namespace NeuraFrame\Contracts\Model;

interface DatabaseTable
{
    /**
    * Get table name of the model
    *
    * @return string
    */
    public function getTable();
}