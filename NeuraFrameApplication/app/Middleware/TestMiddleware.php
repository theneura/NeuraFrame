<?php

namespace App\Middleware;

use NeuraFrame\Contracts\Middleware\MiddlewareInterface;
use NeuraFrame\Http\Request;
use Closure;

class TestMiddleware implements MiddlewareInterface
{
    public function handle(Request $request,Closure $next)
    {
        
    }
}