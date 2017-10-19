<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Orm\MapperModelFactory;
use NeuraFrame\Database\Database;

class MapperModelFactoryTest extends TestCase 
{
    private $factory;

    public function setUp()    
    {
        $dbMock = $this->createMock(NeuraFrame\Database\Database::class);
        $this->factory = new MapperModelFactory($dbMock);
    }

    /**
    * @test
    */
    public function is_createReflectionObject_works_throws_exception()
    {
        $this->expectException(\LogicException::class);
        $this->invokeMethod($this->factory,'createReflectionObject',array('mappedModelTestClassOne'));
    }

    /**
    * @test
    */
    public function is_createReflectionObject_return_correct_object()
    {
        $object = $this->invokeMethod($this->factory,'createReflectionObject',array('mappedModelTestClassTwo'));
        $this->assertInstanceOf(\ReflectionClass::class,$object);
    }

    /**
    * @test
    */
    public function is_createInstanceOfMappedModel_throws_exception()
    {
        $this->expectException(\LogicException::class);
        $this->invokeMethod($this->factory,'createInstanceOfMapperModel',array('mappedModelTestClassTwo'));
    }

    /**
    * @test
    */
    public function is_createInstanceOfMappedModel_works_as_expected()
    {
        $object = $this->invokeMethod($this->factory,'createInstanceOfMapperModel',array('mappedModelTestClassThree'));
        $this->assertInstanceOf('mappedModelTestClassThree',$object);
        $this->assertInstanceOf('NeuraFrame\Orm\Mapper',$object);
    }

    /**
    * @test
    */
    public function is_addNewMapper_model_throws_exception()
    {
        $this->expectException(NeuraFrame\Exceptions\ClassNotExistsException::class);
        $object = $this->invokeMethod($this->factory,'addNewMapperModel',array('nonExistingclassName'));
    }

    /**
    * @test
    */
    public function is_addNewMapper_model_works_as_excepted()
    {
        $object = $this->invokeMethod($this->factory,'addNewMapperModel',array('mappedModelTestClassThree'));
        $resoult = $this->invokeMethod($this->factory,'hasMapperModel',array('mappedModelTestClassThree'));
        $this->assertTrue($resoult);
    }

    /**
    * @test
    */
    public function is_getMapper_works_as_expected()
    {
        $object = $this->factory->getMapper('mappedModelTestClassThree');
        $this->assertInstanceOf('mappedModelTestClassThree',$object);
        $this->assertInstanceOf('NeuraFrame\Orm\Mapper',$object);
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

class mappedModelTestClassOne
{
    private function __construct(){}
}
class mappedModelTestClassTwo
{
    
}
class mappedModelTestClassThree extends NeuraFrame\Orm\Mapper
{
    protected $modelClassName = 'test';
}
