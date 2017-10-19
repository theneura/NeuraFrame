<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Database\SqlStatemants\InsertStatemant;

class InsertStatemantTest extends TestCase 
{
    /**
    * @test
    */
    public function constructor_works_as_expected()
    {
            $statemant = new InsertStatemant(['name' => 'Jon', 'id' => 5]);
            $resoult = $statemant->table('users')->getSql();

            $expected = 'INSERT INTO users (name, id) VALUES (?, ?)';
            $this->assertEquals($resoult,$expected);
    }

    /**
    * @test
    */
    public function getSql_works_throws_exception_when_there_are_not_bindings()
    {
            $this->expectException(NeuraFrame\Exceptions\SqlStatemantException::class);

            $statemant = new InsertStatemant();
            $resoult = $statemant->columns(['name','id'])
                                ->table('users')
                                ->getSql();
    }

    /**
    * @test
    */
    public function getSql_works_as_expected()
    {
        $statemant = new InsertStatemant();
        $resoult = $statemant->columns(['name','id'])
                                ->addData(['testName','testId'])
                                ->table('users')
                                ->getSql();
            $expected = 'INSERT INTO users (name, id) VALUES (?, ?)';
            $this->assertEquals($expected,$resoult);
    }
}