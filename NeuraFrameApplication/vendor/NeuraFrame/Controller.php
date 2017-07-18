<?php

namespace NeuraFrame;

use NeuraFrame\Contracts\Application\ApplicationInterface;

abstract class Controller
{
    /**
    * Application object
    *
    * @var \NeuraFrame\ApplicationInterface
    */
    protected $app;

    /**
    * Constructor
    *
    * @param \NeuraFrame\ApplicationInterface $app
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