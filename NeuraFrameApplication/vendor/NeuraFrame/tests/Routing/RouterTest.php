<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Routing\Router;
use NeuraFrame\Application;
use NeuraFrame\Http\Request;
use NeuraFrame\Contracts\Middleware\MiddlewareInterface;

class RouterTest extends TestCase 
{
    private $router;

    public function setUp()    
    {
        $app = new Application();
        $this->router = $app->router;
    }

    /**
    * @test
    */
    public function is_addRoute_works_as_expected()
    {
        $this->assertInstanceOf(NeuraFrame\Routing\Route::class,$this->router->addRoute('/someUrl/:args1/:args2','AdminController@index'));
    }

    /**
    * @test
    */
    public function is_callControllerMethod_works_as_expected()
    {
        $controllerMock = $this->getMockBuilder('App\\Controllers\\HomeController')
                                ->disableOriginalConstructor()
                                ->getMock();
        $controllerMock->expects($this->once())
                        ->method('index')
                        ->willReturn(true);

        $method = 'index';

        $requestMock = new Request();

        $this->assertTrue($this->invokeMethod($this->router,'callControllerMethod',[$controllerMock,$method,$requestMock]));
    }

    /**
    * @test
    */
    public function is_handleRequest_work_as_expected()
    {
        $routeFactoryMock = $this->getMockBuilder('NeuraFrame\\Routing\\RouteFactory')
                                ->disableOriginalConstructor()
                                ->getMock();
        $controllerFactoryMock = $this->getMockBuilder('NeuraFrame\\Controller\\ControllerFactory')
                                ->disableOriginalConstructor()
                                ->getMock();

        $routeMock = $this->getMockBuilder('NeuraFrame\\Routing\\Route')
                            ->disableOriginalConstructor()
                            ->getMock();

        $routeMock->expects($this->once())
                    ->method('controllerMethod')
                    ->willReturn('index');
        
        $routeFactoryMock->expects($this->once())
                        ->method('getProperRoute')
                        ->willReturn($routeMock);

        $controllerMock = $this->getMockBuilder('App\\Controllers\\HomeController')
                                ->disableOriginalConstructor()
                                ->getMock();
        $controllerMock->expects($this->once())
                        ->method('index')
                        ->willReturn(true);

        $controllerFactoryMock->expects($this->once())
                            ->method('getProperController')
                            ->willReturn($controllerMock);

        $testRouter = new Router(new Application(),$routeFactoryMock,$controllerFactoryMock);
        $this->assertTrue($testRouter->handleRequest(new Request()));
    }

    /**
    * @test
    */
    public function is_handleRequest_work_as_expected_second_test()
    {
        $routeFactoryMock = $this->getMockBuilder('NeuraFrame\\Routing\\RouteFactory')
                                ->disableOriginalConstructor()
                                ->getMock();
        $controllerFactoryMock = $this->getMockBuilder('NeuraFrame\\Controller\\ControllerFactory')
                                ->disableOriginalConstructor()
                                ->getMock();

        $routeMock = $this->getMockBuilder('NeuraFrame\\Routing\\Route')
                            ->disableOriginalConstructor()
                            ->getMock();

        $routeMock->expects($this->once())
                    ->method('controllerMethod')
                    ->willReturn('index');

        $routeMock->expects($this->once())
                    ->method('hasMiddleware')
                    ->willReturn(false);
        
        $routeFactoryMock->expects($this->once())
                        ->method('getProperRoute')
                        ->willReturn($routeMock);

        $controllerMock = $this->getMockBuilder('App\\Controllers\\HomeController')
                                ->disableOriginalConstructor()
                                ->getMock();
        $controllerMock->expects($this->once())
                        ->method('index')
                        ->willReturn('indexMethod');

        $controllerFactoryMock->expects($this->once())
                            ->method('getProperController')
                            ->willReturn($controllerMock);

        $testRouter = new Router(new Application(),$routeFactoryMock,$controllerFactoryMock);
        $this->assertEquals('indexMethod',$testRouter->handleRequest(new Request()));
    }

    /**
    * @test
    */
    public function is_handleRequest_work_as_expected_third_test()
    {
        $routeFactoryMock = $this->getMockBuilder('NeuraFrame\\Routing\\RouteFactory')
                                ->disableOriginalConstructor()
                                ->getMock();
        $controllerFactoryMock = $this->getMockBuilder('NeuraFrame\\Controller\\ControllerFactory')
                                ->disableOriginalConstructor()
                                ->getMock();

        $routeMock = $this->getMockBuilder('NeuraFrame\\Routing\\Route')
                            ->disableOriginalConstructor()
                            ->getMock();

        $routeMock->expects($this->once())
                    ->method('hasMiddleware')
                    ->willReturn(true);

        $routeMock->expects($this->once())
                    ->method('getMiddlewares')
                    ->willReturn(['MiddlewareTestClass']);
        
        $routeFactoryMock->expects($this->once())
                        ->method('getProperRoute')
                        ->willReturn($routeMock);

        $controllerMock = $this->getMockBuilder('App\\Controllers\\HomeController')
                                ->disableOriginalConstructor()
                                ->getMock();

        $controllerFactoryMock->expects($this->once())
                            ->method('getProperController')
                            ->willReturn($controllerMock);

        $testRouter = new Router(new Application(),$routeFactoryMock,$controllerFactoryMock);
        $this->assertEquals('middlewareTestClass',$testRouter->handleRequest(new Request()));
    }

    /**
    * @test
    */
    public function is_handleRequest_work_as_expected_fourth()
    {
        $routeFactoryMock = $this->getMockBuilder('NeuraFrame\\Routing\\RouteFactory')
                                ->disableOriginalConstructor()
                                ->getMock();
        $controllerFactoryMock = $this->getMockBuilder('NeuraFrame\\Controller\\ControllerFactory')
                                ->disableOriginalConstructor()
                                ->getMock();

        $routeMock = $this->getMockBuilder('NeuraFrame\\Routing\\Route')
                            ->disableOriginalConstructor()
                            ->getMock();

        $routeMock->expects($this->once())
                    ->method('controllerMethod')
                    ->willReturn('index');

        $routeMock->expects($this->once())
                    ->method('hasMiddleware')
                    ->willReturn(true);

        $routeMock->expects($this->once())
                    ->method('getMiddlewares')
                    ->willReturn(['secondMiddlewareTestClass']);
        
        $routeFactoryMock->expects($this->once())
                        ->method('getProperRoute')
                        ->willReturn($routeMock);

        $controllerMock = $this->getMockBuilder('App\\Controllers\\HomeController')
                                ->disableOriginalConstructor()
                                ->getMock();
        $controllerMock->expects($this->once())
                        ->method('index')
                        ->willReturn('indexMethod');

        $controllerFactoryMock->expects($this->once())
                            ->method('getProperController')
                            ->willReturn($controllerMock);

        $testRouter = new Router(new Application(),$routeFactoryMock,$controllerFactoryMock);
        $this->assertEquals('indexMethod',$testRouter->handleRequest(new Request()));
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

class MiddlewareTestClass implements MiddlewareInterface
{
    public function handle(Request $request, Closure $closure)
    {
        return 'middlewareTestClass';
    }
}

class SecondMiddlewareTestClass implements MiddlewareInterface
{
    public function handle(Request $request, Closure $closure)
    {
        return $closure($request);
    }
}