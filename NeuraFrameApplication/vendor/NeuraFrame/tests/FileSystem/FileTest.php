<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\FileSystem\File;
use NeuraFrame\Contracts\Application\ApplicationInterface;
use NeuraFrame\Exceptions\FileNotExistsException;
use NeuraFrame\Application;

class FileTest extends TestCase 
{
    private $file;
    private $mockAppInterface;

    public function setUp()    
    {
        $app = new Application();
        $this->file = $app->file;

        $this->mockAppInterface = $this->getMockBuilder('NeuraFrame\Contracts\Application\ApplicationInterface')->getMock();
        $this->mockAppInterface->expects($this->any())
                        ->method('getBasePath')
                        ->will($this->returnValue('sample/path'));
    }
    /**
    * @test
    */
    public function is_base_path_correct()
    {
        $file = new File($this->mockAppInterface);
        $this->assertEquals('sample'.DIRECTORY_SEPARATOR.'path',$file->getBasePath());
    }

    /**
    * @test
    */
    public function is_exists_returns_valid_boolean()
    {
        $this->assertFalse($this->file->exists('nonExistingPath/nonExisting'));
        $this->assertTrue($this->file->exists($this->file->toVendor('NeuraFrame/tests/FileSystem/FileTest.php')));
    }

    /**
    * @test
    */
    public function is_scanDir_return_valid_arrays()
    {
        $path = $this->file->toNeuraFrame('FileSystem/');
        $array = $this->file->scanDir($path);
        $this->assertCount(1,$array);
        $this->assertEquals('File.php',array_shift($array));
        
    }

    /**
    * @test
    */
    public function is_scanDir_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $path = "nonExistingDirectory";
        $array = $this->file->scanDir($path);        
    }

    /**
    * @test
    */
    public function is_require_throws_exception()
    {
        $this->expectException(FileNotExistsException::class);
        $path = "nonExistingDirectory";
        $array = $this->file->require($path);
    }

    /**
    * @test
    */
    public function is_require_return_valid_value()
    {
        $data = $this->file->require('config/database.php');
        $this->assertTrue(sizeof($data) > 0);
    }

    /**
    * @test
    */
    public function is_extension_return_valid_value()
    {
        $this->assertTrue($this->file->isExtension('sample.php','php'));
        $this->assertFalse($this->file->isExtension('sample.ext','php'));
    }

    /**
    * @test
    */
    public function is_toApp_return_valid_value()
    {
        $expected = $this->file->to('app/');
        $returned = $this->file->toApp();
        $this->assertEquals($expected,$returned);
    }

    /**
    * @test
    */
    public function is_toPublic_return_valid_value()
    {
        $expected = $this->file->to('../public/');
        $returned = $this->file->toPublic();
        $this->assertEquals($expected,$returned);
    }

    /**
    * @test
    */
    public function is_toUploads_return_valid_value()
    {
        $expected = $this->file->to('../public/uploads/');
        $returned = $this->file->toUploads();
        $this->assertEquals($expected,$returned);
    }
}