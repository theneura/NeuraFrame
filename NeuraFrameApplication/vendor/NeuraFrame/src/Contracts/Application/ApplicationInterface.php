<?php

namespace NeuraFrame\Contracts\Application;

interface ApplicationInterface
{
    /**
    * Return service from Application Container
    *
    * @param string $key
    * @return mixed
    */
    public function __get($key);

    /**
    * Return base path of application
    *
    * @return string
    */
    public function getBasePath();
}