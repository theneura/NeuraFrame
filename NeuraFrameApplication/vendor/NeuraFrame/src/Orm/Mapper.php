<?php

namespace NeuraFrame\Orm;

use NeuraFrame\Contracts\Database\DatabaseInterface;
use NeuraFrame\Database\QueryBuilder;
use NeuraFrame\Exceptions\ClassNotExistsException;
use StdClass;

abstract class Mapper
{
    /**
    * Name of database table required for creating SQL queries. If value is not set
    * by user, mapper will generate name using class name defined by user (modelClassName), and adding letter s at end.
    *
    * @var string
    */
    protected $table;

    /**
    * Primary key for model table in database
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
    * Name of model class we want to map. Value of this variable is required, so user must define it
    *
    * @param string $modelClassName
    */
    protected $modelClassName;

    /**
    * Interface to database object, used for executing SQL queries
    *
    * @var NeuraFrame\Contracts\Database\DatabaseInterface
    */
    protected $dbInterface;

    /**
    * Instance of temporary queryBuilder used for building queryes 
    *
    * @var NeuraFrame\Database\SqlStatemants\StatemantContainer
    */
    protected $statemant;

    /**
    * Constructor
    *
    * @param NeuraFrame\Contracts\Database\DatabaseInterface $dbInterface
    */
    public function __construct(DatabaseInterface $dbInterface)
    {
        if(!$this->hasModelClassName())
            throw new \InvalidArgumentException('Protected variable $modelClassName is not define. Its required and user must define it!');

        if(!$this->hasTableName())
            $this->table = $this->generateTableName();

        $this->dbInterface = $dbInterface;
    }

    /**
    * Determine if protected variable table is set by user or not
    *
    * @return bool
    */
    protected function hasTableName()
    {
        return isset($this->table);
    }

    /**
    * Determine i protected variable modelClassName is set by user or not
    *
    * @return bool
    */
    protected function hasModelClassName()
    {
        return isset($this->modelClassName);
    }

    /**
    * Auto generate table name. Its assume that table name is className + letter s
    *
    * @return string
    */
    protected function generateTableName()
    {
        $classNameSpace = explode('\\',strtolower($this->modelClassName."s"));
        return end($classNameSpace);
    } 

    /**
    * Return new instance of QueryBuilder object
    *
    * @return NeuraFrame\Database\QueryBuilder
    */
    protected function queryBuilder()
    {
        return new QueryBuilder();
    }

    /**
    * Determine if temporarly queryBuilder is set
    *
    * @return bool
    */
    protected function isStatemantSet()
    {
        return isset($this->statemant);
    }

    /**
    * Parse array of stdObjects to model objects
    *
    * @param array $stdObjects
    * @return array
    */
    private function parseStdArray(array $stdObjects)
    {
        $modelObjects = [];

        foreach($stdObjects as $stdObject)
            $modelObjects[] = $this->parseStdObject($stdObject);
        
        return $modelObjects;
    }

    /**
    * Parse std object to model object
    *
    * @param \stdClass $stdObject
    * @return mixed
    */
    private function parseStdObject(stdClass $stdObject)
    {
        if(!$stdObject)
            return null;

        $modelObject = $this->createNewModelInstance();
        $modelObject->setData((array)$stdObject);
        
        return $modelObject;
    }

    /**
    * Creating new instance of modelClass
    *
    * @return mixed
    */
    private function createNewModelInstance()
    {
        $fullClassName = 'App\\Models\\'.$this->modelClassName;

        if(!class_exists($fullClassName))
            throw new ClassNotExistsException($fullClassName);

        return new $fullClassName();
    }

    /**
    * Determine if passed object is instance of expected class
    *
    * @param mixed $model
    * @return bool
    */
    protected function isInstanceOfExpectedClass($model)
    {
        return is_a($model,'App\\Models\\'.$this->modelClassName);
    }

    /**
    * Collect all objects from database, parse it and return as array of model class objects
    *
    * @return array
    */
    public function all()
    {
        $query = $this->queryBuilder()->select()->from($this->table);
        return $this->parseStdArray($this->dbInterface->execute($query)->fetchAll());
    }

    /**
    * Find model object by primary key in database and return it
    *
    * @param string $primaryKeyValue
    * @return mixed
    */
    public function find($primaryKeyValue)
    {
        $query = $this->queryBuilder()->select()->from($this->table)->where($this->primaryKey.' = '.$primaryKeyValue);
        $stdObject = $this->dbInterface->execute($query)->fetch();

        return $stdObject ? $this->parseStdObject($stdObject) : null;
    }

    /**
    * Update model to database
    *
    * @param mixed model
    */
    public function update($model)
    {
        if(!$this->isInstanceOfExpectedClass($model))
            throw new \InvalidArgumentException('Passed model object for updating is not instance of expected class! Model class is: '.get_class($model));
        
        $query = $this->queryBuilder()->update($model->getData())->table($this->table)->where($this->primaryKey. ' = '.$model->__get($this->primaryKey));
        return $this->dbInterface->execute($query)->rowCount();
    }

    /**
    * Create new model to database
    *
    * @param mixed model
    */
    public function save($model)
    {
        if(!$this->isInstanceOfExpectedClass($model))
            throw new \InvalidArgumentException('Passed model object for creating is not instance of expected class! Model class is: '.get_class($model));
        
        $modelData = $model->getData();

        if(array_key_exists($this->primaryKey,$modelData))
            unset($modelData[$this->primaryKey]);

        $query = $this->queryBuilder()->insert($modelData)->table($this->table);
        return $this->dbInterface->execute($query)->rowCount();
    }

    /**
    * Delete model from database by passed primaryKeyValue
    *
    * @param $primaryKeyValue
    * @param mixed model
    */
    public function delete($primaryKeyValue)
    {        
        $query = $this->queryBuilder()->delete()->table($this->table)->where($this->primaryKey.' = '.$primaryKeyValue);
        return $this->dbInterface->execute($query)->rowCount();
    }

    /**
    * Adding where clause to temporary queryBuilder object
    *
    * @param string $where
    * @return $this
    */
    public function where($where)
    {
        $this->checkStatemant();
       
        $this->statemant->where($where);
        return $this;
    }

    /**
    * Adding where clause to temporary queryBuilder object
    *
    * @param string $orderByKey
    * @param string $orderByValue 
    * @return $this
    */
    public function orderBy($orderByKey,$orderByValue = 'ASC')
    {
        $this->checkStatemant();
        
        $this->statemant->orderBy($orderByKey,$orderByValue);
        return $this;
    }

    /**
    * Adding where clause to temporary queryBuilder object
    *
    * @param int $limit 
    * @return $this
    */
    public function limit($limit,$offset = 0)
    {
        $this->checkStatemant();
        
        $this->statemant->limit($limit,$offset);
        return $this;
    }

    /**
    * Execute temporary SQLStatemant ($statemant) and return array of model objects
    *
    * @return array
    */
    public function get()
    {
        if(!$this->isStatemantSet())
           throw new \InvalidArgumentException('Statemant is not created. You need to use some clause(where,orderBy,limit) before calling this method');
        
        $this->statemant->table($this->table);
        $models = $this->parseStdArray($this->dbInterface->execute($this->statemant)->fetchAll());

        unset($this->statemant);

        return $models;
    }

    /**
    * Check if statemant is instantiate, and if not will create new selection statemant
    *
    * @return void
    */
    private function checkStatemant()
    {
         if(!$this->isStatemantSet())
            $this->statemant = $this->queryBuilder()->select();
    }
}