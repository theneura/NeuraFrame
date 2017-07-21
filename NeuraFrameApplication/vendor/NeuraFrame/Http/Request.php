<?php

namespace NeuraFrame\Http;

use NeuraFrame\Exceptions\MissingTokenException;
use NeuraFrame\Exceptions\InvalidTokenException;
use NeuraFrame\Session;
use NeuraFrame\Routing\Route;
use NeuraFrame\Contracts\Application\ApplicationInterface;

class Request 
{
    /**
    * url that need to be handeled
    *
    * @var string
    */
    private $url;

    /**
    * base url
    *
    * @var string
    */
    private $baseUrl;

    /**
    * ApplicationInterface object
    *
    * @var \NeuraFrame\ApplicationInterface 
    */
    private $app;

    /**
    * Constructor
    *
    * @param \NeuraFrame\ApplicationInerface $app
    */
    public function __construct()
    {
        $this->prepare();
    }

    /**
    * Get Value from _SERVER by the given key
    *
    * @param string $key
    * @param mixed default
    * @return mixed
    */
    private function server($key,$default = null)
    {
        return array_get($_SERVER,$key,$default);
    }

    /**
    * Preparing uri for handeling
    *
    * @return void
    */
    private function prepare()
    {
        $scriptName = dirname($this->server('SCRIPT_NAME'));
        $requestUri = $this->server('REQUEST_URI');

        if(strpos($requestUri, '?') !== false)
            list($requestUri,$queryString) = explode('?',$requestUri);

        $this->url = preg_replace('#^'.$scriptName.'#','',$requestUri);
        $this->baseUrl = $this->server('REQUEST_SCHEME') . '://' . $this->server('HTTP_HOST') . $scriptName . '/';
    }

    /**
    * Get Current Request Method
    *
    * @return string
    */
    public function method()
    {
        return $this->server('REQUEST_METHOD');
    }

    /**
    * Get full url of the script
    *
    * @return string
    */
    public function baseUrl()
    {
        return $this->baseUrl;
    }

    /**
    * Get relative url (clean url)
    *
    * @return string
    */
    public function url()
    {
        return $this->url;
    }

    /**
    * Get Value from _GET by the given key
    *
    * @param string $key
    * @param mixed default
    * @return mixed
    */
    public function get($key,$default = null)
    {
        return filterSpecialCharacters(array_get($_GET,$key,$default));
    }

    public function getAll()
    {
        array_walk_recursive($_GET,'filterSpecialCharacters');
        return $_GET;
    }

    public function postAll()
    {
        array_walk_recursive($_POST,'filterSpecialCharacters');
        return $_POST;
    }

    /**
    * Get Value from _POST by the given key
    *
    * @param string $key
    * @param mixed default
    * @return mixed
    */
    public function post($key,$default = null)
    {
        filterSpecialCharacters($_POST[$key]);
        return $_POST[$key];
    }   

    /**
    * Get values from _File by given key
    *
    * @param string $key
    * @param mixed default
    * @return mixed
    */ 
    public function file($key,$default = null)
    {
        return $_FILES[$key];
    }

    public function filesAll()
    {
        return $_FILES;
    }

    public function getFullUrlFromBase($url)
    {
        return rtrim($this->baseUrl(),'/') . $url;
    }

    /**
    * Return currently required method
    *
    * @return string
    */
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
    * Check CSRF token if method is POST
    *
    * @throws \NeuraFrame\Exceptions\InvalidTokenException
    * @return void
    */
    public function checkToken()
    {
        if($this->getMethod() == 'POST')
        {
            if(!isset($_POST['token'])){
                throw new MissingTokenException();  
            }else{
                if(!Session::checkToken($_POST['token']))
                    throw new InvalidTokenException();
                unset($_POST['token']);
            }
            
        } 
        Session::setCSRF();            
    }
}