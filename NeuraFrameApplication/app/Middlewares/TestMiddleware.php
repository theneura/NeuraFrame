<?php

namespace App\Middleware;

use NeuraFrame\Contracts\Middleware\MiddlewareInterface;
use NeuraFrame\Http\Request;
use Closure;

class TestMiddleware implements MiddlewareInterface
{
	/**
	* Middleware function called in between HTTP request and controller method 
	* 
	* @param NeuraFrame\Http\Request $request
	* @param \Closure $next
	*/
    public function handle(Request $request,Closure $next)
    {
        
    }
}