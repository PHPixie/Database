<?php
namespace PHPixieTests\DB\Driver\Mongo;

/**
 * @coversDefaultClass \PHPixie\DB\Driver\Mongo\Query
 */
class QueryTest extends \PHPixieTests\DB\QueryTest
{
    public function setUp()
    {
        parent::setUp();
        $this->connection = $this->getMock('\PHPixie\DB\Driver\Mongo\Connection', array('run'), array(), '', null, false);
    }

    protected function query()
    {
        return new \PHPixie\DB\Driver\Mongo\Query($this->db, $this->conditionsMock, $this->connection, $this->parser, null, 'select');
    }

    protected function mockParser()
    {
        return $this->getMock('\PHPixie\DB\Driver\Mongo\Parser', array('parse'), array(null, null, null, null, null));
    }

    public function testCollection()
    {
        $this->getSetTest('collection', 'fairies');
    }

    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        $query = $this->query();
        $runner = new \PHPixie\DB\Driver\Mongo\Query\Runner();
        $this->parser
                ->expects($this->any())
                ->method('parse')
                ->with ($query)
                ->will($this->returnValue($runner));

        $this->connection
                ->expects($this->once())
                ->method('run')
                ->will($this->returnValue('a'));

        $this->assertEquals('a', $query->execute());
    }
}
