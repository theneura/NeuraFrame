<?php

namespace NeuraFrame\Contracts\Middleware;

use NeuraFrame\Http\Request;
use Closure;

interface MiddlewareInterface
{
    /**
    * This method is called before controller method
    *
    * @param NeuraFrame\Http\Request $request
    * @param \Closure $next
    * @return mixed
    */
    public function handle(Request $request,Closure $next);
}