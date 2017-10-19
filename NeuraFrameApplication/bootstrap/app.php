<?php

use NeuraFrame\Application;
use NeuraFrame\Http\Request;
use NeuraFrame\Http\Response;
use App\Routes;

//Creating new NeuraFrameApplication, and passing root dir path as parameter
$app =  new Application(__DIR__.'/../');

//Register all available routes
Routes::setUp($app);

//Handeling current request
$responseContent = $app->router->handleRequest(new Request());
$response = new Response($responseContent);
$response->send();

