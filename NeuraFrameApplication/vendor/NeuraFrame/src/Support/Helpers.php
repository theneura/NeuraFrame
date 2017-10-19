<?php

namespace NeuraFrame\Support;

class Helpers
{
    public static function pre($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}