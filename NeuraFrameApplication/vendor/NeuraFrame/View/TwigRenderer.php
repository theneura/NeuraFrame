<?php

namespace NeuraFrame\View;

use NeuraFrame\Contracts\Application\ApplicationInterface;
use NeuraFrame\Session;
use NeuraFrame\Http\Request;

class TwigRenderer
{
    /**
    * Application Container object
    *
    * @var \NeuraFrame\ApplicationInterface
    */
    private $app;

    /**
    * Twig object for rendering views
    * 
    * @var \Twig_Enviroment
    */
    private $twig;

    /**
    * Constructor
    * 
    * @param \NeuraFrame\ApplicationInterface $app
    */
    public function __construct(ApplicationInterface $app)
    {
        $this->app = $app;
        $loader = new \Twig_Loader_FileSystem($this->app->file->to('App/Views'));
        $this->twig = new \Twig_Environment($loader);
        $this->registerTwigFunctions();
    }

    /**
    * Register Twig functions
    *
    *
    */
    public function registerTwigFunctions()
    {
        $route = new \Twig_Function('route',function($routeName,$data = array()){
            $routeUrl = $this->app->router->route($routeName)->url;
            $baseUrl = $this->app->request->baseUrl();
            $finalUrl = $baseUrl. ltrim($routeUrl,'/');
            if(sizeof($data) > 0)
            {
                $finalUrl .= '?'. http_build_query($data);
            }
            return rtrim($finalUrl,'&');
        });

        $csrfToken = new \Twig_Function('token',function()
        {
            return Session::get('csrf_token');
        });

        $assetFolder = new \Twig_Function('asset',function($path = '')
        {
            $newPath = '';
            $urlParts = substr_count($this->app->request->url(),'/');
            for($i=0;$i<$urlParts - 1; $i++)
                $newPath .= '../';
            return $newPath.$path;
        });

        $this->twig->addFunction($route);
        $this->twig->addFunction($csrfToken);
        $this->twig->addFunction($assetFolder);
    }

    /**
    * Rendering view using twig
    *
    * @param string $viewPath
    * @param array $data
    */
    public function render($viewPath,array $data = [])
    {
       return $this->twig->render($viewPath,$data);
    }
}