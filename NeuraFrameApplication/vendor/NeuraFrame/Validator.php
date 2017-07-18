<?php

namespace NeuraFrame;


use NeuraFrame\Exceptions\DataIsNotSetException;
use NeuraFrame\Exceptions\MethodIsNotCallableException;
use NeuraFrame\Contracts\Application\ApplicationInterface;

class Validator
{
    /**
    * Application interface object
    *
    * @var \NeuraFrame\Contracts\Application\ApplicationInterface
    */
    private $app;

    private $errors = [];

    /**
    * Constructor
    *
    * @param \NeuraFrame\Contracts\Application\ApplicationInterface $app
    */
    public function __construct(ApplicationInterface $app)
    {
        $this->app = $app;
    }

    /**
    * Validating data by given rules
    *
    * @param array $data
    * @param array $rulesSet
    */
    public function validate($data = array(),$rulesSet = array())
    {
        $this->errors = array();
        foreach($rulesSet as $dataKey => $rulesValues)
        {
            $rules = explode('|',$rulesValues);
            foreach($rules as $rule)
            {
                $ruleMethod = $this->getRuleMethod($rule);
                if(!is_callable([$this,$ruleMethod]))
                    throw new MethodIsNotCallableException([$ruleMethod]);
                call_user_func([$this,$ruleMethod],$dataKey,$rule,$data);
            }
        }
        
        return $this;
    }


    /**
    * Return true if there is no errors in error container
    *
    * @return bool
    */
    public function isValid()
    {
        return empty($this->errors);
    }

    /**
    * Get all errors
    *
    * @return array
    */
    public function errors()
    {
        return $this->errors;
    }

    /**
    * Get rule and parse it to valid format
    *
    * @param string $rule 
    * @return string
    */
    private function getRuleMethod($rule)
    {
        $ruleSet = explode(':',$rule); 
        return array_shift($ruleSet);
    }

    /**
    * If data is not set, append error to error container
    *
    * @param string $dataKey
    * @param string $rule
    * @param array $data
    * @return void
    */
    private function required($dataKey,$rule,$data = array())
    {
        if(!isset($data[$dataKey]) || strlen($data[$dataKey]) == 0)
            $this->appendError($dataKey,' is required!');
    }   

    /**
    * If data size is more than max value, append error to error container
    *
    * @param string $dataKey
    * @param string $ruleSet
    * @param array $data
    * @return void
    */
    private function max($dataKey,$ruleSet,$data = array())
    {
        list($rule,$maxValue) = explode(':',$ruleSet);
        if(isset($data[$dataKey]) && strlen($data[$dataKey]) > $maxValue)
            $this->appendError($dataKey,' is required to be less than '.$maxValue.' characters long!');
            
    }  

    /**
    * If data size less than max value, append error to error container
    *
    * @param string $dataKey
    * @param string $ruleSet
    * @param array $data
    * @return void
    */
    private function min($dataKey,$ruleSet,$data = array())
    {
        list($rule,$minValue) = explode(':',$ruleSet);
        if(isset($data[$dataKey]) && strlen($data[$dataKey]) < $minValue)
            $this->appendError($dataKey,' is required to be more than '.$minValue.' characters long!');
            
    }  

    /**
    * If data is not unique in database table, append error to error container
    *
    * @param string $dataKey
    * @param string $ruleSet
    * @param array $data
    * @return void
    */
    private function unique($dataKey,$ruleSet,$data = array())
    {
        list($rule,$databaseTable) = explode(':',$ruleSet);
        if(isset($data[$dataKey]))
            if(!is_null($this->app->database->where($dataKey. '="' .$data[$dataKey].'"')->fetch($databaseTable)))
                $this->appendError($dataKey,' allready exists!');
    } 

    /**
    * If data is valid email, append error to error container
    *
    * @param string $dataKey
    * @param string $rule
    * @param array $data
    * @return void
    */
    private function email($dataKey,$rule,$data = array())
    {
        if(isset($data[$dataKey]) && !filter_var($data[$dataKey],FILTER_VALIDATE_EMAIL))
                $this->appendError($dataKey,' is not valid email address!');
    }   

    /**
    * Appending Error message to error container
    *
    * @param string $dataKey 
    * @param string $message
    * @return void
    */
    private function appendError($dataKey,$message)
    {
        $this->errors[$dataKey][] = $message;        
    }
}