<?php

namespace NeuraFrame\Http;

use NeuraFrame\Application;

class Redirect
{
    /**
    * Redirect to route by name
    *
    * @param string $route
    * @param array $data
    */
    public static function route($route,$data = array())
    {
        $app = Application::getInstance();
        $response = new Response();

        $routeUrl = $app->router->route($route)->url;
        $baseUrl = $app->request->baseUrl();
        $finalUrl = $baseUrl. ltrim($routeUrl,'/');
        if(sizeof($data) > 0)
        {
            $finalUrl .= '?'.http_build_query($data);
        }
        $response->setHeader('Location',$finalUrl);
        return $response->send();
    }
}