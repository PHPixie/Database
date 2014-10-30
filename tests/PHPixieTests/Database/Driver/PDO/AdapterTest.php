<?php
namespace PHPixieTests\Database\Driver\PDO;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Adapter
 */
abstract class AdapterTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $adapter;
    protected $connection;
    protected $result;
    protected $pdoStub;

    protected $listColumnsQuery;
    protected $listColumnsColumn;
    protected $connectionValueMap = array();
    
    protected $transactionQueries = array(
        'begin'    => 'BEGIN TRANSACTION',
        'commit'   => 'COMMIT',
        'rollback' => 'ROLLBACK',
    );

    public function setUp()
    {
        $this->connection = $this->getMock('\PHPixie\Database\Driver\PDO\Connection', array(), array(), '', false);
        $this->result = $this->getMock('\PHPixie\Database\Driver\PDO\Result', array('getField', 'get'), array(), '', false);
        $this->pdoStub = $this->getMock('Stub', array('lastInsertId'), array(), '', false );
    }

    /**
     * @covers ::listColumns
     * @covers ::__construct
     */
    public function testListColumns()
    {
        $this->prepareQueryColumnAssertion($this->listColumnsQuery, 'getField', $this->listColumnsColumn, array('id', 'name'));
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
    
    /**
     * @covers ::beginTransaction
     * @covers ::commitTransaction
     * @covers ::rollbackTransaction
     * @covers ::<protected>
     */
    public function testTransaction()
    {
        foreach($this->transactionQueries as $type => $query) {
            $method = $type.'Transaction';
            $this->connection
                ->expects($this->at(0))
                ->method('execute')
                ->with($query);
            $this->adapter->$method();
        }
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
