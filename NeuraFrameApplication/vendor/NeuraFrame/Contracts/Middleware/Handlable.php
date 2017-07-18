<?php

namespace NeuraFrame\Contracts\Middleware;

use NeuraFrame\Http\Request;

interface Handlable
{
    /**
    * Handle incoming request
    *
    * @param \NeuraFrame\Http\Request $request 
    * @return mixed
    */
    public function handle(Request $request);
}