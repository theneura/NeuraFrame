<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Routing\Route;
use App\Middlewares\TestMiddleware;

class RouteTest extends TestCase 
{
    private $route;

    public function setUp()    
    {
        $this->route = new Route('/sample','GET','Test@testing',array('first','second'));
    }

    /**
    * @test
    */
    public function is_route_constructor_do_as_expected()
    {
        $route_t = new Route('/321','POST','AdminController@something');

        $this->assertTrue($route_t->urlIsMatching('/321'));
        $this->assertTrue($route_t->methodIsMatching('POST'));
        $this->assertEquals('AdminController',$route_t->controllerAlias());
        $this->assertEquals('something',$route_t->controllerMethod());

        $route_t = new Route('/321','POST','AdminController');
        $this->assertEquals('AdminController',$route_t->controllerAlias());
        $this->assertEquals('index',$route_t->controllerMethod());
    }

    /**
    * @test
    */
    public function is_route_argumentsMatch_works()
    {
        $this->assertFalse($this->route->argumentsMatch(array('first')));
        $this->assertTrue($this->route->argumentsMatch(array('first','second')));
    }

    /**
    * @test
    */
    public function is_route_argumentsMatch_throw_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->assertTrue($this->route->argumentsMatch(array('first','third')));
    }

    /**
    * @test
    */
    public function is_route_addMiddleware_throws_exception()
    {
        $this->expectException(NeuraFrame\Exceptions\ClassNotExistsException::class);
        $this->route->addMiddleware('RandomMiddleware');
    }

    /**
    * @test
    */
    public function is_route_hasMiddleware_works_as_expected()
    {
        $this->route->addMiddleware('TestMiddleware')
                    ->addMiddleware('TestMiddleware');
        $this->assertTrue($this->route->hasMiddleware());
    }

    /**
    * @test
    */
    public function is_route_getMiddlewares_works_as_expected()
    {
        $this->route->addMiddleware('TestMiddleware')
                    ->addMiddleware('TestMiddleware');
        $this->assertCount(2,$this->route->getMiddlewares());
    }

    /**
    * @test
    */
    public function is_route_getName_return_as_expected()
    {
        $this->route->setName('exampleRoute');
        $this->assertEquals('exampleRoute',$this->route->name());
    }

    
}