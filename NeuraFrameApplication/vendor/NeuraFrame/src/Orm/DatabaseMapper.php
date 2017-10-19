<?php

namespace NeuraFrame\Orm;

use NeuraFrame\Contracts\Orm\MapperModelFactoryInterface;


class DatabaseMapper
{
    /**
    * Interface to factory object, used for creating and acessing mappedModels
    *
    * @var NeuraFrame\Contracts\Database\MappedModelFactoryInterface
    */
    private $mapperModelFactory;

    /**
    * Constructor
    *
    * @param NeuraFrame\Contracts\Orm\MapperModelFactoryInterface $mapperModelFactory
    */
    public function __construct(MapperModelFactoryInterface $mapperModelFactory)
    {
        $this->mapperModelFactory = $mapperModelFactory;
    }

    /**
    * Return instnace of mapper class from mapperModelFactory object
    *
    * @param string $mapperClassAlias
    * @return mixed
    */
    public function getMapper($mapperClassAlias)
    {
        return $this->mapperModelFactory->getMapper($mapperClassAlias);
    }
}