<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Controller\ControllerFactory;
use NeuraFrame\Application;

class ControllerFactoryTest extends TestCase 
{
    protected $controllerFactory;

    public function setUp()
    {
        $this->controllerFactory = new ControllerFactory(new Application());
    }

    public function test_instance()
    {
        $this->assertInstanceOf(ControllerFactory::class,$this->controllerFactory);
    }
      
    public function test_controller()
    {
        $controllerClass = 'eqweqw';
        $controller = $this->controllerFactory->controller($controllerClass);
        $this->assertInstanceOf('App\\Controllers\\'.$controllerClass,$controller);
    }
}