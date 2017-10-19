<?php

namespace NeuraFrame\View;

use NeuraFrame\Contracts\View\TemplateEngineInterface;
use NeuraFrame\Contracts\Application\ApplicationInterface;
use NeuraFrame\Contracts\FileSystem\FilePathInterface; 

class TwigTemplateEngine implements TemplateEngineInterface
{
    /**
    * Instance of the application interface
    *
    * @var NeuraFrame\Contracts\Application\ApplicationInterface $app;
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
    * @param NeuraFrame\Contracts\Application\ApplicationInterface $app
    * @param NeuraFrame\Contracts\FileSystem\FilePathInterface $file
    */
    public function __construct(ApplicationInterface $app, FilePathInterface $file)
    {
        $this->app = $app;
        $loader = new \Twig_Loader_FileSystem($file->toApp('Views'));
        $this->twig = new \Twig_Environment($loader);
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