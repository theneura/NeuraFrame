<?php

namespace NeuraFrame;

use NeuraFrame\Contracts\Application\ApplicationInterface;

abstract class Controller
{
    /**
    * Instance of the application container
    *
    * @var NeuraFrame\Contracts\Application\ApplicationInterface
    */
    private $app;

    /**
    * Controller constructor
    *
    * @param NeuraFrame\Contracts\Application\ApplicationInterface $app
    */
    public function __construct(ApplicationInterface $app)
    {
        $this->app = $app;
    }

    /**
    * Call shared application objects dynamically
    *
    * @param string $key
    * @return mixed
    */
    public function __get($key)
    {
        return $this->app->__get($key);
    }
}