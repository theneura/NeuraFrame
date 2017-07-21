<?php

namespace NeuraFrame\Http;

use NeuraFrame\Application;
use NeuraFrame\Session;

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

    public static function back($data = array())
    {   
        $app = Application::getInstance();   
        if(Session::has('lastRoute'))
        {
            $route = Session::get('lastRoute');
            $newData = $route->passedArguments;
            foreach($data as $dataKey => $dataValue)
            {
                if(array_key_exists($dataKey,$newData))
                    $newData[$dataKey] = $dataValue;
            }
            return self::route($route->getName(),$newData);
        }
        //TODO: throw exception or something
    }
}