<?php

namespace NeuraFrame\Contracts\View;

interface TemplateEngineInterface
{
    /**
    * Rendering view using some sort of template engine
    * @param string $viewPath
    * @param array $data
    */
    public function render($viewPath,array $data = []);
}