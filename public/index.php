<?php

/**
* NeuraFrame is a mini PHP framework for creating MVC applications
*
* @author Dusan Djordjevic <theneura@gmail.com>
*/

/**
* Require a autoloader generated using composer
*/
require __DIR__. '/../NeuraFrameApplication/bootstrap/autoload.php';

/**
* Require a NeuraFrame framework
*/
$app = require __DIR__. '/../NeuraFrameApplication/bootstrap/app.php';

$responseContent = $app->router->getResponseContent();
$response = new NeuraFrame\Http\Response($responseContent);
$response->send();