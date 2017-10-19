<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Database\SqlClauses\Limit;

class LimitTest extends TestCase 
{
   /**
    * @test
    */
   public function is_limit_return_sql_as_expected_first_test()
   {
        $limit = new Limit;
        $limit->set(2);

        $expected = ' LIMIT 2';
        $this->assertEquals($expected, $limit->getSql());
   }

   /**
    * @test
    */
   public function is_limit_return_sql_as_expected_second_test()
   {
        $limit = new Limit;

        $expected = '';
        $this->assertEquals($expected, $limit->getSql());
   }
}