<?php

namespace NeuraFrame\Contracts\Support\Configuration;

interface ConfigMiddlewareInterface
{
    /**
    * Get configurations for middleware
    *
    * @return array
    */
    public function getMiddlewareConfig();
}