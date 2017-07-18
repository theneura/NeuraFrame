<?php

namespace NeuraFrame;

class Cookie
{
    public static function set($key,$value,$hours = 1800)
    {
        setcookie($key,$value,time() + $hours * 3600,'','',false,true);
    }
    public static function get($key,$default = null)
    {
        return array_get($_COOKIE,$key,$default);
    }
    public static function has($key)
    {
        return array_key_exists($key,$_COOKIE);
    }
    public static function remove($key)
    {
        setcookie($key,null,-1);
        unset($_COOKIE[$key]);
    }
    public static function all()
    {
        return $_COOKIE;
    }
    public static function destroy()
    {
        foreach(array_keys(self::all()) as $key)
        {
            self::remove($key);
        }
        unset($_COOKIE);
    }
}