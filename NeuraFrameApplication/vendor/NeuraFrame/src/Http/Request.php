<?php

namespace NeuraFrame\Http;

class Request 
{
    /**
    * Full url, that need to be handeled
    *
    * @var string
    */
    private $url;

    /**
    * Parser, base url
    *
    * @var string
    */
    private $baseUrl;

    /**
    * Requested method
    *
    * @var string
    */
    private $method;

    /**
    * Constructor
    */
    public function __construct()
    {
        $this->prepareUrl();
    }

    /**
    * Parser url, get full url and base url
    *
    * @return void
    */
    private function prepareUrl()
    {
        $scriptDir = $this->getScriptDir();
        $requestUri = $this->getRequestUri();
            
        $this->url = $this->parseUrl($scriptDir,$requestUri);
        $this->baseUrl = $this->parseBaseUrl($scriptDir);
        $this->method = $this->getRequestMethod();
    }

    /**
    * Get script name directory
    *
    * @return string
    */
    private function getScriptDir()
    {
        return dirname($this->server('SCRIPT_NAME'));
    }

    /**
    * Get requestUri without query string
    *
    * @return string
    */
    private function getRequestUri()
    {
        $requestUri = $this->server('REQUEST_URI');

        if(strpos($requestUri,'?') !== false)
            list($requestUri,$queryString) = explode('?',$requestUri);

        return $requestUri ? $requestUri : '/';
    }

    /**
    * Parse and return full url
    *
    * @param string $scriptDir
    * @param string $requestUri
    * @return string
    */
    private function parseUrl($scriptDir,$requestUri)
    {
        return preg_replace('#^'.$scriptDir.'#','',$requestUri);
    }

    /**
    * Parse and return base url
    *
    * @param string $scriptDir
    * @return string
    */
    private function parseBaseUrl($scriptDir)
    {
        return $this->server('REQUEST_SCHEME') . '://' . $this->server('HTTP_HOST') . $scriptDir. '/';
    }

    /**
    * Get value from _SERVER array by given key
    *
    * @param string $key
    * @return mixed
    */
    private function server($key)
    {
        return $this->array_get($_SERVER,$key,null);
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
        return $this->filterValue($this->array_get($_GET,$key,$default));
    }

    /**
    * Get all Values from _GET array
    *
    * @return array
    */
    public function getAll()
    {        
        return $_GET;
    }

    /**
    * Get Value from _POST by the given key
    *
    * @param string $key
    * @param mixed default
    * @return mixed
    */
    public function post($key,$filter = true,$default = null)
    {        
        return $this->filterValue($this->array_get($_POST,$key,$default));
    }

    /**
    * Get Value from _FILE by the given key
    *
    * @param string $key
    * @param mixed default
    * @return mixed
    */
    public function file($key,$default = null)
    {        
        return $this->array_get($_FILES,$key,$default);
    }

    /**
    * Return currently required method
    *
    * @return string
    */
    public function method()
    {
        return $this->method;
    }

    /**
    * Get request method
    *
    * @return string
    */
    private function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
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
    * Get the value from the given array for the given key if found,
    * otherwise get the default value
    *
    * @param array $array
    * @param string|int $key
    * @param mixed $default
    */
    private function array_get($array,$key,$default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }

    /**
    * Convert string to filter specialcharacters
    *
    * @param string $value
    * @return string
    */
    private function filterValue($value)
    {
        return htmlspecialchars($value,ENT_QUOTES,'UTF-8');
    }
}