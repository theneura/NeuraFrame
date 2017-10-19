<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Database\SqlClauses\Wheres;

class WheresTest extends TestCase 
{
   /**
    * @test
    */
   public function is_wheres_return_sql_as_expected_first_test()
   {
        $wheres = new Wheres;
        $wheres[] = 'id = 1';

        $expected = ' WHERE id = 1';
        $this->assertEquals($expected, $wheres->getSql());
   }

   /**
    * @test
    */
   public function is_wheres_return_sql_as_expected_second_test()
   {
        $wheres = new Wheres;

        $expected = '';
        $this->assertEquals($expected, $wheres->getSql());
   }

   /**
    * @test
    */
   public function is_wheres_return_sql_as_expected_third_test()
   {
        $wheres = new Wheres;
        $wheres[] = 'id = 1';
        $wheres[] = 'name = testName';

        $expected = ' WHERE id = 1 AND name = testName';
        $this->assertEquals($expected, $wheres->getSql());
   }
}