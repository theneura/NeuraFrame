<?php

namespace App\Controllers;

use NeuraFrame\Controller;
use NeuraFrame\Http\Request;

class HomeController extends Controller
{
	/**
	* Rendering HomeView.html page on request
	*
	* @param NeuraFrame\Http\Request $request
	*/
    public function index(Request $request)
    {
        // Call template engine to render view
        // First parameter -> name of view file 
        // Second parameter -> array of data sending to view file
        return $this->view->render('HomeView.html',[
			'message'	=>	'Hello World'
		]);
    }
}