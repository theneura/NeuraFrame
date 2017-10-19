<?php

namespace NeuraFrame\Contracts\Database\SqlStatemants;

interface StatemantInterface
{
    /**
    * Return bindings array
    *
    * @return array
    */
    public function getBindings();

    /**    
    * Fetching SQL query string
    *    
    * @return string    
    */    
    public function getSql();

    /**
    * Determine if there are binded parameters
    * 
    * @return bool
    */
    public function hasBindings();
}