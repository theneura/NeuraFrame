<?php

use PhpUnit\Framework\TestCase;
use NeuraFrame\Orm\DatabaseMapper;

class DatabaseMapperTest extends TestCase 
{
    /**
    * @test
    */
    public function is_constructor_and_getMapper_works_as_expected()
    {
        $mapperModelFactoryMock = $this->createMock(NeuraFrame\Orm\MapperModelFactory::class);
        $mapperModelFactoryMock->expects($this->once())
                                ->method('getMapper')
                                ->will($this->returnValue('passedTest'));

        $dbMapper = new DatabaseMapper($mapperModelFactoryMock);

        $this->assertEquals('passedTest',$dbMapper->getMapper('test'));
    }
}