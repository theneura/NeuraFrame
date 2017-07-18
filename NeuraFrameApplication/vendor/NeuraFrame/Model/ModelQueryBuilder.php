<?php

namespace NeuraFrame\Model;

use NeuraFrame\Contracts\Application\ApplicationInterface;
use NeuraFrame\Contracts\Model\DatabaseTable;

class ModelQueryBuilder
{
    /**
    * Get all data from database for given model
    *
    * @param \NeuraFrame\ApplicationInterface $app 
    * @param \NeuraFrame\Contracts\DatabaseTable $model;
    * @return array
    */
    public static function all(ApplicationInterface $app,DatabaseTable $model)
    {
        $modelCollection = new ModelCollection($app->database->fetchAll($model->getTable()));
        return $modelCollection->toArray(get_class($model));
    }

    /**
    * Test if given key exists on table in database
    *
    * @param \NeuraFrame\ApplicationInterface $app 
    * @param \NeuraFrame\Contracts\DatabaseTable $model
    * @param string $column
    * @return bool
    */
    
    public static function haveKey(ApplicationInterface $app,DatabaseTable $model,$column)
    {
        return $app->database->keyExist($model->getTable(),$column);
    }

    /**
    * Searching for model by id in database, and return it
    *
    * @param \NeuraFrame\ApplicationInterface $app 
    * @param \NeuraFrame\Contracts\DatabaseTable $model
    * @param int $id
    * @return \NeuraFrame\Model
    */
    public static function find(ApplicationInterface $app,DatabaseTable $model,$id)
    {
        $test = new ModelCollection($app->database->table($model->getTable())->where("id = ".$id)->fetch());
        return $test->toModel(get_class($model));
    }

    /**
    * Where clause for database query
    *
    * @param \NeuraFrame\ApplicationInterface $app 
    * @param \NeuraFrame\Contracts\DatabaseTable $model
    * @param string $where
    * @return \NeuraFrame\Database\DatabaseQueryInterface
    */
    public static function where(ApplicationInterface $app,DatabaseTable $model,$where)
    {
        $app->database->table($model->getTable())->where($where);
        return $model;        
    }

    /**
    * Where clause from non static function
    *
    * @param \NeuraFrame\ApplicationInterface $app 
    * @param \NeuraFrame\Contracts\DatabaseTable $model
    * @param string $where
    * @return \NeuraFrame\Model
    */
    public static function whereFromNonStatic(ApplicationInterface $app,DatabaseTable $model,$where)
    {
        return ModelQueryBuilder::where($app,$model,$where);
    }

    /**
    * Limit clause from non static function
    *
    * @param \NeuraFrame\ApplicationInterface $app 
    * @param \NeuraFrame\Contracts\DatabaseTable $model
    * @param string $limit
    * @return \NeuraFrame\Model
    */
    public static function limitFromNonStatic(ApplicationInterface $app,DatabaseTable $model,$limit)
    {
        return ModelQueryBuilder::limit($app,$model,$limit);
    }

    /**
    * Order by clause from non static function
    *
    * @param \NeuraFrame\ApplicationInterface $app 
    * @param \NeuraFrame\Contracts\DatabaseTable $model
    * @param string $row
    * @param string $orderBy
    * @return \NeuraFrame\Model
    */
    public static function orderByFromNonStatic(ApplicationInterface $app,DatabaseTable $model,$row,$orderBy)
    {
        return ModelQueryBuilder::orderBy($app,$model,$row,$orderBy);
    }

    /**
    * Limit clause for database query
    *
    * @param \NeuraFrame\ApplicationInterface $app 
    * @param \NeuraFrame\Contracts\DatabaseTable $model
    * @param int $limit
    * @return \NeuraFrame\Database\DatabaseQueryInterface
    */
    public static function limit(ApplicationInterface $app,DatabaseTable $model,$limit)
    {
        $app->database->table($model->getTable())->limit($limit);
        return $model;
    }

    /**
    * Order by clause for database query
    *
    * @param \NeuraFrame\ApplicationInterface $app 
    * @param \NeuraFrame\Contracts\DatabaseTable $model
    * @param int $row
    * @param string $orderBy
    * @return \NeuraFrame\Database\DatabaseQueryInterface
    */
    public static function orderBy(ApplicationInterface $app,DatabaseTable $model,$row,$orderBy ="ASC")
    {
        $app->database->table($model->getTable())->orderBy($row,$orderBy);
        return $model;
    }

    /**
    * Update data to database and return query
    *
    * @param \NeuraFrame\ApplicationInterface $app 
    * @param \NeuraFrame\Contracts\DatabaseTable $model
    * @param array $data
    * @return \NeuraFrame\Database\DatabaseQueryInterface
    */
    public static function updateData(ApplicationInterface $app,DatabaseTable $model,$data)
    {
        return $app->database->setData($data)->where("id = ".$data->id)->update($model->getTable());
    }

    /**
    * Save new data to database and return query
    *
    * @param \NeuraFrame\ApplicationInterface $app 
    * @param \NeuraFrame\Contracts\DatabaseTable $model
    * @param array $data
    * @return \NeuraFrame\Database\DatabaseQueryInterface
    */
    public static function saveNewData(ApplicationInterface $app,DatabaseTable $model,$data)
    {
        return $app->database->setData($data)->insert($model->getTable());
    }

    /**
    * Delete model data from database and return query
    *
    * @param \NeuraFrame\ApplicationInterface $app 
    * @param \NeuraFrame\Contracts\DatabaseTable $model
    * @param array $id
    * @return \NeuraFrame\Database\DatabaseQueryInterface
    */
    public static function deleteData(ApplicationInterface $app,DatabaseTable $model,$id)
    {
        return $app->database->where("id = ".$id)->delete($model->getTable());
    }
}