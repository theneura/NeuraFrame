<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Application;
use NeuraFrame\View\TwigTemplateEngine;
use NeuraFrame\FileSystem\File;

class TwigTemplateEngineTest extends TestCase 
{
    /**
    * @test
    */
    public function is_render_work_as_expected()
    {
        $fileMock = $this->getMockBuilder('NeuraFrame\\FileSystem\\File')
                                ->disableOriginalConstructor()
                                ->getMock();

        $fileMock->expects($this->once())
                    ->method('toApp')
                    ->willReturn(__DIR__);

        $view = new TwigTemplateEngine(new Application(),$fileMock);

        $resoult = $view->render('testPage.twig');
        $this->assertEquals('Testing string',$resoult);
    }
}