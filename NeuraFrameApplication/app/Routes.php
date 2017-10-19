<?php

namespace App;

use NeuraFrame\Contracts\Application\ApplicationInterface;

class Routes
{
    /**
    * Here we create every route we need for our application
    *
    * @param \NeuraFrame\Contracts\Application\ApplicationInterface $app
    */
    public static function setUp(ApplicationInterface $app)
    {
        $app->router->addRoute('/','HomeController@index','GET')->name('getIndex');
    }
}