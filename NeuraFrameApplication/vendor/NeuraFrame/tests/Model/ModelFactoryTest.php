<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Model\ModelFactory;
use NeuraFrame\Application;

class ModelFactoryTest extends TestCase 
{
    protected $modelFactory;

    public function setUp()
    {
        $this->modelFactory = new ModelFactory(new Application());
    }

    public function test_instance()
    {
        $this->assertInstanceOf(ModelFactory::class,$this->modelFactory);
    }

    
    public function test_model()
    {
        $modelClass = 'testModel';
        $model = $this->modelFactory->model($modelClass);
    }
}