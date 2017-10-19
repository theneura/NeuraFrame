<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Application;

class ApplicationTest extends TestCase 
{
    private $app;

    public function setUp()    
    {
        $this->app = new Application();
    }

    /**
    * @test
    */
    public function is_constructor_setting_basePath()
    {
        $testApp = new Application(__DIR__);
        $this->assertEquals(__DIR__,$testApp->getBasePath());
    }

    /**
    * @test
    */
    public function is_setBasePath_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->app->setBasePath('nonExistingPath/nonExistingPath');
    }
}