<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Database\SqlStatemants\SelectStatemant;

class SelectStatemantTest extends TestCase 
{
   /*
   * @test
   */
   public function test_getSql_works_as_expected()
   {
       $statemant = new SelectStatemant();
       $resoult = $statemant->select('*')
                            ->from('users')
                            ->where('id = 1')
                            ->orderBy('id','DSC')
                            ->limit(2)
                            ->getSql();

        $expected = 'SELECT * FROM users WHERE id = 1 ORDER BY id DSC LIMIT 2 OFFSET 0';
        $this->assertEquals($expected,$resoult);
   }

   /*
   * @test
   */
   public function test_getSql_works_as_expected_when_selects_passed_by_constructor()
   {
       $statemant = new SelectStatemant('name');
       $resoult = $statemant->from('users')
                            ->where('id = 1')
                            ->orderBy('id','DSC')
                            ->limit(2)
                            ->getSql();

        $expected = 'SELECT name FROM users WHERE id = 1 ORDER BY id DSC LIMIT 2 OFFSET 0';
        $this->assertEquals($expected,$resoult);
   }
}