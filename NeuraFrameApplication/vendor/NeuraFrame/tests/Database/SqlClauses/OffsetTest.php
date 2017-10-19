<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Database\SqlClauses\Offset;

class OffsetTest extends TestCase 
{
   /**
    * @test
    */
   public function is_offset_return_sql_as_expected_first_test()
   {
        $offset = new Offset;
        $offset->set(2);

        $expected = ' OFFSET 2';
        $this->assertEquals($expected, $offset->getSql());
   }

   /**
    * @test
    */
   public function is_offset_return_sql_as_expected_second_test()
   {
        $offset = new Offset;

        $expected = '';
        $this->assertEquals($expected, $offset->getSql());
   }
}