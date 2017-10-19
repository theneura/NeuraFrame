<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Routing\RouteFactory;
use NeuraFrame\Application;
use NeuraFrame\Http\Request;

class RouteFactoryTest extends TestCase 
{
    private $routeFactory;

    public function setUp()    
    {
        $app = new Application();
        $this->routeFactory = $app->routeFactory;
    }

    /**
    * @test
    */
    public function is_addRoute_works_as_expected()
    {
        $this->routeFactory->addRoute('/something/:param1/:param2','GET','AdminController@index');
        $this->assertTrue($this->invokeMethod($this->routeFactory,'routeExists',array('/something/')));
    }

    /**
    * @test
    */
    public function is_getProperRoute_works_as_expected()
    {
        $this->routeFactory->addRoute('/','GET','AdminController@index');

        $_SERVER['SCRIPT_NAME'] = '/myMvc-TDD/NeuraFrame/public/index.php';
        $_SERVER['REQUEST_URI'] = '/myMvc-TDD/NeuraFrame/public/';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET = [];

        $route = $this->routeFactory->getProperRoute(new Request());
        
        $this->assertInstanceOf(NeuraFrame\Routing\Route::class,$route);
    }

    /**
    * @test
    */
    public function is_getProperRoute_throws_exception()
    {
        $this->routeFactory->addRoute('/otherRoute','GET','AdminController@index');
        $_SERVER['SCRIPT_NAME'] = '/myMvc-TDD/NeuraFrame/public/index.php';
        $_SERVER['REQUEST_URI'] = '/myMvc-TDD/NeuraFrame/public/';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET = [];

        $this->expectException(\OutOfBoundsException::class);
        $route = $this->routeFactory->getProperRoute(new Request());
    }

    /**
    * @test
    */
    public function is_getProperRoute_throws_exception_when_method_is_not_match()
    {
        $this->routeFactory->addRoute('/','GET','AdminController@index');
        $_SERVER['SCRIPT_NAME'] = '/myMvc-TDD/NeuraFrame/public/index.php';
        $_SERVER['REQUEST_URI'] = '/myMvc-TDD/NeuraFrame/public/';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_GET = [];

        $this->expectException(\OutOfBoundsException::class);
        $route = $this->routeFactory->getProperRoute(new Request());
    }

    /**
    * @test
    */
    public function is_addSame_route_throws_exception()
    {
        $this->expectException(NeuraFrame\Exceptions\RouteExistsException::class);
        $this->routeFactory->addRoute('/something/:param1/:param2','GET','AdminController@index');
        $this->routeFactory->addRoute('/something/:param1/:param2','GET','AdminController@index');
    }

    /**
    * Call protected/private method of a class.
    *
    * @param object &$object    Instantiated object that we will run method on.
    * @param string $methodName Method name to call
    * @param array  $parameters Array of parameters to pass into method.
    *
    * @return mixed Method return.
    */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}