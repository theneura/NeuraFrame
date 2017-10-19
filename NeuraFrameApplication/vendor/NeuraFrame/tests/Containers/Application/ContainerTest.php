<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Containers\Application\Container;

class ApplicationContainerTest extends TestCase 
{
    private $container;

    public function setUp()    
    {
        $this->container = new Container();
    }

    /**
    * @test
    */
    public function is_resolveName_return_bined_class()
    {
        $returned = $this->invokeMethod($this->container,'resolveName',array('testClass'));
        $this->assertInstanceOf('testClass',$returned);
    }

    /**
    * @test
    */
    public function is_resolve_return_singleton_value()
    {
        $this->container->singleton('test','testClass');
        $resoult = $this->container->resolve('test');
        $this->assertInstanceOf('testClass',$resoult);
        $this->assertTrue($this->invokeMethod($this->container,'isSingleton',array('test')));
    }

    /**
    * @test
    */
    public function is_getBinding_throws_exception()
    {
        $this->expectException(\OutOfRangeException::class);
        $this->invokeMethod($this->container,'getBinding',array('nonExistingKey'));
    }

    /**
    * @test
    */
    public function is_getClassByKey_return_key_if_key_is_not_registered()
    {
        $key = 'sampleKey';
        $this->assertEquals($key,$this->invokeMethod($this->container,'getClassByKey',array($key)));
    }

    /**
    * @test
    */
    public function is_createInstance_throws_exception_if_class_not_exists()
    {
        $this->expectException(\InvalidArgumentException::class);
        $className = 'nonExistingClass';
        $this->invokeMethod($this->container,'createInstance',array($className));
    }

    /**
    * @test
    */
    public function is_createReflection_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $className = 'testInterface';
        $this->invokeMethod($this->container,'createReflection',array($className));
    }

    /**
    * @test
    */
    public function is_buildDependecies_returns_args_with_parent_param()
    {
        $className = 'testClass_two';
        $reflection = new \ReflectionClass($className);
        $dependecies = $reflection->getConstructor()->getParameters();
        $arguments = $this->invokeMethod($this->container,'buildDependecies',array([],$dependecies));
        $this->assertInstanceOf('NeuraFrame\Containers\Application\Container',array_shift($arguments));
    }

    /**
    * @test
    */
    public function is_resolvingArguments_does_not_return_anything_if_there_is_no_constructor()
    {
        $className = 'testInterface';
        $reflection = new \ReflectionClass($className);
        $return = $this->invokeMethod($this->container,'resolvingArguments',array($reflection));
        $this->assertEquals(null,$return);
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

class testClass implements testInterface
{
    public function __construct(){}
}
interface testInterface
{

}
class testClass_two
{
    public function __construct(NeuraFrame\Containers\Application\Container $cont){}
}