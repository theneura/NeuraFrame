<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Database\SqlStatemants\UpdateStatemant;

class UpdateStatemantTest extends TestCase 
{
   /*
   * @test
   */
   public function test_getSql_works_throws_exception_when_there_are_not_bindings()
   {
        $this->expectException(NeuraFrame\Exceptions\SqlStatemantException::class);

        $statemant = new UpdateStatemant();
        $resoult = $statemant->where('id > 5')
                            ->table('users')
                            ->getSql();
   }

   /*
   * @test
   */
   public function test_getSql_works_as_Expected()
   {
        $statemant = new UpdateStatemant();
        $resoult = $statemant->columns(['id','name'])
                            ->addData(['22','TestName'])
                            ->table('users')
                            ->where('id = 5')
                            ->getSql();
        $expected = 'UPDATE users SET id = ? , name = ? WHERE id = 5';
        $this->assertEquals($expected,$resoult);
   }

   /*
   * @test
   */
   public function test_getSql_works_as_Expected_when_pairs_ar_passed_in_constructor()
   {
        $statemant = new UpdateStatemant([
            'id'    =>  22,
            'name'  =>  'TestName'
        ]);
        $resoult = $statemant->table('users')
                            ->where('id = 5')
                            ->getSql();
        $expected = 'UPDATE users SET id = ? , name = ? WHERE id = 5';
        $this->assertEquals($expected,$resoult);
   }
}