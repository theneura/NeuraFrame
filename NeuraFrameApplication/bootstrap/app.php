<?php

use NeuraFrame\Application;
use App\Routes;

//Creating new NeuraFrame application
$app = new Application(__DIR__.'/../');

//Register routes
Routes::register($app);


return $app;