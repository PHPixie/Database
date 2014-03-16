<?php
namespace PHPixieTests\Database\Driver\Mongo;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Query
 */
class QueryTest extends \PHPixieTests\Database\QueryTest
{
    public function setUp()
    {
        parent::setUp();
        $this->connection = $this->getMock('\PHPixie\Database\Driver\Mongo\Connection', array('run'), array(), '', null, false);
    }

    protected function query()
    {
        return new \PHPixie\Database\Driver\Mongo\Query($this->database, $this->conditionsMock, $this->connection, $this->parser, null, 'select');
    }

    protected function mockParser()
    {
        return $this->getMock('\PHPixie\Database\Driver\Mongo\Parser', array('parse'), array(null, null, null, null, null));
    }

    public function testCollection()
    {
        $this->getSetTest('collection', 'fairies');
    }

    /**
     * @covers ::batchData
     * @covers ::getBatchData
     * @covers ::data
     */
    public function testGetSetBatchData()
    {
        $data = array(
            array('test' => 5),
            array('test2' => 6)
        );
        
        $this->assertEquals($this->query, $this->query->batchData($data));
        $this->assertEquals($data, $this->query->getBatchData());
        $this->query->data(array());
        $this->assertEquals(null, $this->query->getBatchData());
    }

    /**
     * @covers \PHPixie\Database\Query::data
     * @covers ::data
     * @covers ::getData
     */
    
    public function testData() {
        parent::testData();
    }
    
    /**
     * @covers ::selectSingle
     * @covers ::getSelectSingle
     */
    
    public function testSelectSingle() {
        $this->getSetTest('selectSingle', true, false);
    }
    
    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        $query = $this->query();
        $runner = new \PHPixie\Database\Driver\Mongo\Query\Runner();
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
