<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Database\SqlClauses\Selects;

class SelectsTest extends TestCase 
{
   /**
    * @test
    */
   public function is_selects_return_sql_as_expected_first_test()
   {
        $selects = new Selects;
        $selects[] = 'name';

        $expected = 'name';
        $this->assertEquals($expected, $selects->getSql());
   }

   /**
    * @test
    */
   public function is_selects_return_sql_as_expected_second_test()
   {
        $selects = new Selects;

        $expected = '*';
        $this->assertEquals($expected, $selects->getSql());
   }

   /**
    * @test
    */
   public function is_selects_return_sql_as_expected_third_test()
   {
        $selects = new Selects;
        $selects[] = 'name';
        $selects[] = 'id';

        $expected = 'name,id';
        $this->assertEquals($expected, $selects->getSql());
   }
}