<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Application;
use NeuraFrame\Http\Request;
use NeuraFrame\Middleware\Pipeline;
use NeuraFrame\Contracts\Middleware\MiddlewareInterface;

class PipelineTest extends TestCase 
{
    private $pipeline;

    public function setUp()    
    {
        $this->pipeline = new Pipeline(new Application());
    }

    /**
    * @test
    */
    public function is_then_throws_exception()
    {
        $middlewares = ['NeuraFrame\\RandomClass','NeuraFrame\\OtherRandomClass'];
        $this->expectException(NeuraFrame\Exceptions\VariableIsNotSetException::class);

        $this->pipeline->setMiddlewares($middlewares)
                        ->then(function(){
                            echo 'hi';
                        });
    }

    /**
    * @test
    */
    public function is_then_throw_classNotExistsException()
    {
        $middlewares = ['NeuraFrame\\RandomClass','NeuraFrame\\OtherRandomClass'];
        $request = new Request();

        $this->expectException(NeuraFrame\Exceptions\ClassNotExistsException::class);

        $this->pipeline->sendRequest($request)
                        ->setMiddlewares($middlewares)
                        ->then(function (){
                            echo 'callback';
                        });
    }

    /**
    * @test
    */
    public function is_then_throw_classDontHaveInterfaceException()
    {
        $middlewares = ['FirstPipelineTestClass'];
        $request = new Request();

        $this->expectException(NeuraFrame\Exceptions\ClassDontHaveInterfaceException::class);

        $this->pipeline->sendRequest($request)
                        ->setMiddlewares($middlewares)
                        ->then(function (){
                            echo 'callback';
                        });
    }

    /**
    * @test
    */
    public function is_then_work_as_expected()
    {
        $middlewares = ['SecondPipelineTestClass','ThirdPipelineTestClass'];
        $request = new Request();

        $resoult = $this->pipeline->sendRequest($request)
                        ->setMiddlewares($middlewares)
                        ->then(function (){
                            echo 'callback';
                        });

        $this->assertEquals('test',$resoult);
    }

    /**
    * @test
    */
    public function is_then_work_as_expected_second_test()
    {
        $middlewares = ['SecondPipelineTestClass'];
        $request = new Request();

        $resoult = $this->pipeline->sendRequest($request)
                        ->setMiddlewares($middlewares)
                        ->then(function (){
                            return 'callback';
                        });

        $this->assertEquals('callback',$resoult);
    }

    /**
    * @test
    */
    public function is_then_work_as_expected_third_test()
    {
        $request = new Request();

        $resoult = $this->pipeline->sendRequest($request)
                        ->then(function (){
                            return 'callback';
                        });

        $this->assertEquals('callback',$resoult);
    }
}

class FirstPipelineTestClass
{
    
}
class SecondPipelineTestClass implements MiddlewareInterface
{
    public function handle(Request $request,Closure $next)
    {
        return $next($request);
    }
}

class ThirdPipelineTestClass implements MiddlewareInterface
{
    public function handle(Request $request,Closure $next)
    {
        return 'test';
    }
}