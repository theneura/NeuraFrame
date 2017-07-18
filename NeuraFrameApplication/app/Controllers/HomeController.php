<?php

namespace App\Controllers;

use NeuraFrame\Controller;
use NeuraFrame\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)   
    {
        return $this->twig->render('index.html');
    }
}