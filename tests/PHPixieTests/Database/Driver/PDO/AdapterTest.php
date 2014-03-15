<?php
namespace PHPixieTests\Database\Driver\PDO;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Adapter
 */
abstract class AdapterTest extends \PHPixieTests\AbstractDatabaseTest{
    protected $adapter;
    protected $connection;
    protected $result;
    protected $pdoStub;

    protected $listColumnsQuery;
    protected $listColumnsColumn;
    protected $connectionValueMap = array();

    public function setUp()
    {
        $this->connection = $this->getMock('\PHPixie\Database\Driver\PDO\Connection', array('execute', 'pdo'), array(), '', false);
        $this->result = $this->getMock('\PHPixie\Database\Driver\PDO\Result', array('getColumn', 'get'), array(), '', false);
        $this->pdoStub = $this->getMock('Stub', array('lastInsertId'), array(), '', false );
    }

    /**
     * @covers ::listColumns
     * @covers ::__construct
     */
    public function testListColumns()
    {
        $this->prepareQueryColumnAssertion($this->listColumnsQuery, 'getColumn', $this->listColumnsColumn, array('id', 'name'));
        $this->adapter->listColumns('fairies');
    }

    /**
     * @covers ::insertId
     */
    public function testInsertId()
    {
        $this->connection
                        ->expects($this->any())
                        ->method('pdo')
                        ->with()
                        ->will($this->returnValue($this->pdoStub));

        $this->pdoStub
                        ->expects($this->any())
                        ->method('lastInsertId')
                        ->with ()
                        ->will($this->returnValue(1));

        $this->assertEquals(1, $this->adapter->insertId());
    }

    /**
     * @covers ::insertId
     */
    public function testInsertIdNull()
    {
        $this->connection
                        ->expects($this->any())
                        ->method('pdo')
                        ->with()
                        ->will($this->returnValue($this->pdoStub));

        $this->pdoStub
                        ->expects($this->any())
                        ->method('lastInsertId')
                        ->with ()
                        ->will($this->returnValue(0));

        $this->setExpectedException('\PHPixie\Database\Exception');
        $this->adapter->insertId();
    }

    protected function prepareQueryColumnAssertion($query, $method,  $column, $result)
    {
        $this->connection
                    ->expects($this->at(0))
                    ->method('execute')
                    ->with($query)
                    ->will($this->returnValue($this->result));

        $this->result
                    ->expects($this->once())
                    ->method($method)
                    ->with($column)
                    ->will($this->returnValue($result));
    }

}
