<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Database\SqlClauses\OrderBy;

class OrderByTest extends TestCase 
{
   /**
    * @test
    */
   public function is_orderBy_return_sql_as_expected_first_test()
   {
        $orderBy = new OrderBy;
        $orderBy->set('id');

        $expected = ' ORDER BY id';
        $this->assertEquals($expected, $orderBy->getSql());
   }

   /**
    * @test
    */
   public function is_orderBy_return_sql_as_expected_second_test()
   {
        $orderBy = new OrderBy;

        $expected = '';
        $this->assertEquals($expected, $orderBy->getSql());
   }
}