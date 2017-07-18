<?php

namespace App\Middleware;

use NeuraFrame\Session;
use NeuraFrame\Http\Request;
use NeuraFrame\Http\Redirect;
use NeuraFrame\Contracts\Middleware\Handlable;

class Auth implements Handlable
{
    public function handle(Request $request)
    {
        if(!Session::has('user_id'))
        {
            Redirect::route('admin.getLogin');
        }
        return;
    }
}