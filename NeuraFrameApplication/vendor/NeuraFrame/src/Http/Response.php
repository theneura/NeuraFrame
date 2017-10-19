<?php

namespace NeuraFrame\Http;

class Response 
{
    /**
    * Headers array that will be send
    *
    * @var array
    */
    private $headers = [];

    /**
    * Content that will be send
    *
    * @var string
    */
    private $content = '';

    /**
    * Constructor
    *
    * @param \NeuraFrame\ApplicationInterface $app
    */
    public function __construct($content ='',$status = 200,$headers = array())
    {
        $this->setContent($content);
        $this->setStatusCode($status);
        $this->setHeaders($headers);
    }

    /**
    * Set the response output content
    *
    * @param string $content 
    * @return void
    */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
    * Set the Response headers
    *
    * @param string $headers 
    * @param mixed value
    * @return void
    */
    public function setHeader($header,$value)
    {
        $this->headers[$header] = $value;
    }

    public function setHeaders($hearders = array())
    {
        foreach($hearders as $headerKey => $headerValue)
        {
            $this->setHeader($headerKey,$headerValue);
        }
    }

    public function setStatusCode($statusCode)
    {
        //TODO: implement setStatusCode();
    }

    /**
    * Send the response headers and content
    *
    * @return void
    */
    public function send()
    {
        $this->sendHeaders();
        $this->sendOutput();
    }

    /**
    * Send the response headers
    *
    * @return void
    */
    private function sendHeaders()
    {
        foreach($this->headers as $header => $value){
            header($header . ':' . $value);
        }
    }

    /**
    * Send the response output
    *
    * @return void
    */
    private function sendOutput()
    {
        echo $this->content;
    }
}