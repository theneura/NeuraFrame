<?php

namespace NeuraFrame\Middleware;

use NeuraFrame\Contracts\Application\ApplicationInterface;
use NeuraFrame\Http\Request;
use NeuraFrame\Exceptions\VariableIsNotSetException;
use NeuraFrame\Exceptions\ClassNotExistsException;
use NeuraFrame\Exceptions\ClassDontHaveINterfaceException;
use NeuraFrame\Contracts\Middleware\MiddlewareInterface;
use Closure;

class Pipeline
{
    /**
    * Instance of the application container
    *
    * @var NeuraFrame\Containers\Application\Container $app;
    */
    private $app;

    /**
    * Instance of HTTP request
    *
    * @var NeuraFrame\Http\Request
    */
    private $request;

    /**
    * Array of middleware class names
    * 
    * @var array
    */
    private $middlewares;

    /**
    * Pipeline constructor
    *
    * @param NeuraFrame\Contracts\Application\ApplicationInterface $app
    * @return NeuraFrame\Routing\Pipeline
    */
    public function __construct(ApplicationInterface $app)
    {
        $this->app = $app;
        return $this;
    }

    /**
    * Send request through pipeline
    *
    * @param NeuraFrame\Http\Request $request
    * @return NeuraFrame\Routing\Pipeline
    */
    public function sendRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
    * Handeling middlewares through pipeline
    *
    * @param array $middlewares
    */
    public function setMiddlewares(array $middlewares = null)
    {
        $this->middlewares = $middlewares;
        return $this;
    }

    /**
    * Passing closure for final controller method
    *
    * @param Closure
    * @throws NeuraFrame\Exceptions\VariableIsNotSetException
    * @return mixed
    */
    public function then(Closure $destination)
    {
        if(empty($this->middlewares))
            return $destination($this->request);

        if(!isset($this->request))
            throw new VariableIsNotSetException('requrest is not set');

        $pipeline = array_reduce(   array_reverse($this->middlewares),
                                    $this->getMiddlewareClosureResoult(),
                                    function ($request) use ($destination){
                                        return $destination($request);
                                    });

        return $pipeline($this->request);
    }

    /**
    * Calling middleware Closure and returning resoult
    *
    * @return mixed
    */
    private function getMiddlewareClosureResoult()
    {
        return function ($nextClosure, $middlewareClassName){
            return function ($request) use ($nextClosure, $middlewareClassName)
            {
                return $this->callMiddleware($this->getMiddlewareObject($middlewareClassName),$nextClosure);
            };
        };
    }

    /**
    * Resolving middleware class name and return instance of that class
    *
    * @param string $middlewareClassName
    * @throws NeuraFrame\Exceptions\ClassNotExistsException
    * @return mixed
    */
    private function getMiddlewareObject($middlewareClassName)
    {
        if(!class_exists($middlewareClassName))
                    throw new ClassNotExistsException('Class not exists: '.$middlewareClassName);

        return new $middlewareClassName($this->app);
    }

    /**
    * Calling middleware method handle
    *
    * @param string $middlewareClassName
    * @throws NeuraFrame\Exceptions\ClassDontHaveInterfaceException
    * @return mixed
    */
    private function callMiddleware($middlewareObject,$nextClosure)
    {
        if(!($middlewareObject instanceof MiddlewareInterface))
            throw new ClassDontHaveInterfaceException(get_class($middlewareObject),'NeuraFrame\Contracts\Middleware\MiddlewareInterface');

        return call_user_func_array([$middlewareObject,'handle'],array($this->request,$nextClosure));
    }
}