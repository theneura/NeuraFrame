<?php

namespace NeuraFrame\Security;

use NeuraFrame\Contracts\Application\ApplicationInterface;

class Password
{
    /**
    * Application interface container
    *
    * @var \NeuraFrame\Contracts\Application\ApplicationInterface
    */
    private $app;

    /**
    * Constructor
    *
    * @param \NeuraFrame\Contracts\Application\ApplicationInterface $app
    */
    public function __construct(ApplicationInterface $app)
    {
        $this->app = $app;
    }

    /**
    * Hashing a password
    *
    * @param string $password
    * @return string
    */
    public function hash($password)
    {
        return password_hash($password,PASSWORD_DEFAULT,['cost' => 12]);
    }

    /**
    * Verify password
    *
    * @param string $password
    * @param string $hash
    * @return bool
    */
    public function verify($password,$hash)
    {
        return password_verify($password,$hash);
    }
}