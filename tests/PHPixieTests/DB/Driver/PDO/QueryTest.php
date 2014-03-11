<?php
namespace PHPixieTests\DB\Driver\PDO;

/**
 * @coversDefaultClass \PHPixie\DB\Driver\PDO\Query
 */
class QueryTest extends \PHPixieTests\DB\SQL\QueryTest
{
    protected $resultClass = '\PHPixie\DB\Driver\PDO\Result';
    
    protected function query($type = 'select')
    {
        return new \PHPixie\DB\Driver\PDO\Query($this->db, $this->conditionsMock, $this->connection, $this->parser, null, $type);
    }

    protected function mockParser()
    {
        return $this->quickMock('\PHPixie\DB\Driver\PDO\Mysql\Parser', array('parse'), array());
    }

    protected function mockConnection()
    {
        return $this->getMock('\PHPixie\DB\Driver\PDO\Connection', array('execute'), array(), '', null, false);
    }

}
