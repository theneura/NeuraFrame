<?php

namespace NeuraFrame\Contracts\Orm;

interface MapperModelFactoryInterface
{
    /**
    * Return required mapper model object
    *
    * @param string $mapperClassAlias
    */
    public function getMapper($mapperClassAlias);
}