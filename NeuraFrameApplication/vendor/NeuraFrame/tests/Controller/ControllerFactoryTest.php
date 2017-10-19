<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Controller\ControllerFactory;
use NeuraFrame\Application;

class ControllerFactoryTest extends TestCase 
{
    private $controllerFactory;

    public function setUp()    
    {
        $app = new Application();
        $this->controllerFactory = $app->controllerFactory;
    }
    

    /**
    * @test
    */
    public function is_getController_throws_ControllerNotExistsException()
    {
        $this->expectException(NeuraFrame\Exceptions\ControllerNotExistsException::class);
        $this->invokeMethod($this->controllerFactory,"getController",array("randomController"));
    }

    /**
    * @test
    */
    public function is_createNewControllerObject_throws_ClassNotExistsException()
    {
        $this->expectException(NeuraFrame\Exceptions\ClassNotExistsException::class);
        $this->invokeMethod($this->controllerFactory,"createNewControllerObject",array("nonExistingClassName"));
    }

    /**
    * @test
    */
    public function is_getProperController_calls_as_expected()
    {
        $route = $this->createMock(NeuraFrame\Routing\Route::class);
        $route->expects($this->any())
                ->method('controllerAlias')
                ->will($this->returnValue('HomeController'));
        $route->expects($this->any())
                ->method('controllerMethod')
                ->will($this->returnValue('index'));
        
        $returnedObject = $this->controllerFactory->getProperController($route);
        $this->assertInstanceOf(App\Controllers\HomeController::class,$returnedObject);
        $this->assertInstanceOf(NeuraFrame\Controller::class,$returnedObject);
    }

    /**
    * @test
    */
    public function is_getProperController_throws_methodIsNotCallableException()
    {
        $route = $this->createMock(NeuraFrame\Routing\Route::class);
        $route->expects($this->any())
                ->method('controllerAlias')
                ->will($this->returnValue('HomeController'));
        $route->expects($this->any())
                ->method('controllerMethod')
                ->will($this->returnValue('randomMethod'));
        $this->expectException(NeuraFrame\Exceptions\MethodIsNotCallableException::class);
        $this->assertTrue($this->controllerFactory->getProperController($route));
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