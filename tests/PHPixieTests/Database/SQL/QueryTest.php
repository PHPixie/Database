<?php
namespace PHPixieTests\Database\SQL;

/**
 * @coversDefaultClass \PHPixie\Database\SQL\Query
 */
abstract class QueryTest extends \PHPixieTests\Database\Query\ImplementationTest
{
    public function setUp()
    {
        parent::setUp();
        $this->connection = $this->mockConnection();
    }

    /**
     * @covers ::table
     * @covers ::getTable
     */
    public function testTable()
    {
        $this->assertEquals(null, $this->query->getTable());
        $this->assertEquals($this->query, $this->query->table('a'));
        $this->assertEquals(array('table'=>'a', 'alias' => null), $this->query->getTable());
        $this->assertEquals($this->query, $this->query->table('b', 'c'));
        $this->assertEquals(array('table'=>'b', 'alias' => 'c'), $this->query->getTable());
    }


    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        $query = $this->query();
        $this->parser
                ->expects($this->any())
                ->method('parse')
                ->with ($query)
                ->will($this->returnValue(new \PHPixie\Database\SQL\Expression('pixie', array(5))));

        $this->connection
                ->expects($this->any())
                ->method('execute')
                ->with ('pixie', array(5))
                ->will($this->returnValue('a'));
        $this->assertEquals('a', $query->execute());
    }

}