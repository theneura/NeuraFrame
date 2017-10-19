<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Database\SqlClauses\Table;

class TableTest extends TestCase 
{
   /**
    * @test
    */
   public function is_table_return_sql_as_expected_first_test()
   {
        $table = new Table;
        $table->set('testTable');

        $expected = ' FROM testTable';
        $this->assertEquals($expected, $table->getSql());
   }

   /**
    * @test
    */
   public function is_table_return_sql_throws_exception()
   {
        $table = new Table;

        $this->expectException(NeuraFrame\Exceptions\SqlQueryException::class);
        $table->getSql();
   }

   /**
    * @test
    */
   public function is_table_toString_throws_exception()
   {
        $table = new Table;

        $this->expectException(NeuraFrame\Exceptions\SqlQueryException::class);
        $table->__toString();
   }

   /**
    * @test
    */
   public function is_table_toString_works_as_expected()
   {
        $table = new Table;
        $table->set('testTable');

        $this->assertEquals('testTable',$table);
   }
}