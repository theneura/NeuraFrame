<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Http\Request;

class RequestTest extends TestCase 
{
    private $request;

    public function setUp()    
    {
        $_SERVER['SCRIPT_NAME'] = '/myMvc-TDD/NeuraFrame/public/index.php';
        $_SERVER['REQUEST_URI'] = '/myMvc-TDD/NeuraFrame/public/';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->request = new Request();
    }

    /**
    * @test
    */
    public function is_get_script_dir_returns_correct()
    {
        $expected = '/myMvc-TDD/NeuraFrame/public';
        $returned = $this->invokeMethod($this->request,'getScriptDir');

        $this->assertEquals($expected,$returned);
    }

    /**
    * @test
    */
    public function is_get_request_uri_returned_correct()
    {
        $expected = '/myMvc-TDD/NeuraFrame/public/';
        $returned = $this->invokeMethod($this->request,'getRequestUri');

        $this->assertEquals($expected,$returned);
    }

     /**
    * @test
    */
    public function is_get_request_uri_returned_correct_when_parameters_are_given()
    {
        $_SERVER['REQUEST_URI'] = '/myMvc-TDD/NeuraFrame/public/?param1=2&param2=3';
        $t_request = new Request();

        $expected = '/myMvc-TDD/NeuraFrame/public/';        
        $returned = $this->invokeMethod($t_request,'getRequestUri');
        $this->assertEquals($expected,$returned);
    }

    /**
    * @test
    */
    public function is_get_return_expected()
    {
        $expected = 'testing';
        $_GET['example'] = $expected;        
        $returned = $this->request->get('example');
        $this->assertEquals($expected,$returned);

        $expected = null;
        $returned = $this->request->get('nonExisting',false);
        $this->assertEquals($expected,$returned);
    }

    /**
    * @test
    */
    public function is_get_filtered_return_expected()
    {
        $expected = '&lt;script&gt;Some text&lt;/script&gt;';
        $_GET['example'] = "<script>Some text</script>";
        $returned = $this->request->get('example');
        $this->assertEquals($expected,$returned);
    }

    /**
    * @test
    */
    public function is_post_return_expected()
    {
        $expected = 'testing';
        $_POST['example'] = $expected;        
        $returned = $this->request->post('example');
        $this->assertEquals($expected,$returned);

        $expected = null;
        $returned = $this->request->post('nonExisting',false);
        $this->assertEquals($expected,$returned);
    }

    /**
    * @test
    */
    public function is_method_return_expected()
    {
        $expected = 'GET';    
        $returned = $this->request->method();
        $this->assertEquals($expected,$returned);

        $expected = 'POST';
        $_SERVER['REQUEST_METHOD'] = $expected;   
        $request = new Request()     ;
        $returned = $request->method();
        $this->assertEquals($expected,$returned);
    }

    /**
    * @test
    */
    public function is_file_return_expected()
    {
        $expected = 'sample';
        $_FILES['first_file'] = $expected;
        $returned = $this->request->file('first_file');
        $this->assertEquals($expected,$returned);

        $returned = $this->request->file('nonExisting');
        $this->assertEquals(null,$returned);
    }

    /**
    * @test
    */
    public function is_parseUrl_returns_correct()
    {
        $scriptDir = '/myMvc-TDD/NeuraFrame/public';
        $requestUri = '/myMvc-TDD/NeuraFrame/public/';
        $expected = '/';
        $returned = $this->invokeMethod($this->request,'parseUrl',array($scriptDir,$requestUri));

        $this->assertEquals($expected,$returned);
    }

    /**
    * @test
    */
    public function is_parseBaseUrl_returns_correct()
    {
        $scriptDir = '/myMvc-TDD/NeuraFrame/public';
        $expected = 'http://localhost/myMvc-TDD/NeuraFrame/public/';
        $_SERVER['REQUEST_SCHEME'] = 'http';
        $_SERVER['HTTP_HOST'] = 'localhost';
        $returned = $this->invokeMethod($this->request,'parseBaseUrl',array($scriptDir));

        $this->assertEquals($expected,$returned);
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