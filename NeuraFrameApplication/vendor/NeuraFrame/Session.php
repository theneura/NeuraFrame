<?php

namespace NeuraFrame;

class Session 
{
    /**
    * Start Session
    *
    * @return void
    */
    public static function start()
    {
        ini_set('session.use_only_cookies',1);
        if(! session_id()){
            session_start();            
        }
    }

    /**
    * Set new value to Session
    *
    * @param string $key
    * @param mixed $value
    */
    public static function set($key,$value)
    {
        $_SESSION[$key] = $value;
    }

    /**
    * Generate and store token to Session
    *
    */
    public static function setCSRF()
    {
        $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(16));
    }

    /**
    * Verifying CSRF token
    *
    * @param string $token
    * @return bool
    */
    public static function checkToken($token)
    {
        return $token === $_SESSION['csrf_token'];
    }

    /**
    * Get value from session by the given key
    *
    * @param string $key
    * @return mixed
    */
    public static function get($key, $default = null)
    {
        return array_get($_SESSION,$key,$default);
    }

    /**
    * Determine if the session has the given key
    *
    * @param string $key
    * @return bool
    */
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
    * Remove the given key from session
    *
    * @param string $key
    * @return void
    */
    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }

    /**
    * Get value from session by the given key then remove it
    *
    * @param string $key
    * @return mixed
    */
    public static function pull($key)
    {
        $value = self::get($key);
        self::remove($key);

        return $value;
    }

    /**
    * Get all session data
    *
    * @return array
    */
    public static function all()
    {
        return $_SESSION;
    }

    /**
    * Destroy Session
    *
    * @return void
    */
    public static function destroy()
    {
        session_destroy();
        unset($_SESSION);
    }
}