<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Database\SqlClauses\Joins;

class JoinsTest extends TestCase 
{
   /**
    * @test
    */
   public function is_joins_return_sql_as_expected_first_test()
   {
        $joins = new Joins;
        $joins[] = 'name';
        $joins[] = 'id';

        $expected = ' JOIN name id';
        $this->assertEquals($expected, $joins->getSql());
   }

   /**
    * @test
    */
   public function is_joins_return_sql_as_expected_second_test()
   {
        $joins = new Joins;

        $expected = '';
        $this->assertEquals($expected, $joins->getSql());
   }
}