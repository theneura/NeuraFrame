<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Database\QueryBuilder;

use NeuraFrame\Database\SqlStatemants\InsertStatemant;
use NeuraFrame\Database\SqlStatemants\UpdateStatemant;
use NeuraFrame\Database\SqlStatemants\DeleteStatemant;
use NeuraFrame\Database\SqlStatemants\SelectStatemant;

class QueryBuilderTest extends TestCase 
{
   /**
    * @test
    */
   public function queryBuilder_methods_return_as_expected()
   {
        $query = new QueryBuilder;
        $this->assertInstanceOf(InsertStatemant::class,$query->insert());
        $this->assertInstanceOf(UpdateStatemant::class,$query->update());
        $this->assertInstanceOf(DeleteStatemant::class,$query->delete());
        $this->assertInstanceOf(SelectStatemant::class,$query->select());
   }
}