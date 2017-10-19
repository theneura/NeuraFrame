<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Database\SqlStatemants\DeleteStatemant;

class DeleteStatemantTest extends TestCase 
{
   /*
   * @test
   */
   public function test_getSql_works_as_expected()
   {
       $statemant = new DeleteStatemant();
       $resoult = $statemant->where('id > 5')
                            ->table('users')
                            ->getSql();
        $expected = 'DELETE FROM users WHERE id > 5';
        $this->assertEquals($expected,$resoult);
   }
}