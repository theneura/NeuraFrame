<?php

namespace App\Controllers;

use NeuraFrame\Controller;
use NeuraFrame\Http\Request;
use NeuraFrame\Support\Helpers;
use NeuraFrame\Database\QueryBuilder;

use App\Mappers\ExperimentMapper;
use App\Models\Experiment;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        
        return $this->view->render('HomeView.html');
    }
}