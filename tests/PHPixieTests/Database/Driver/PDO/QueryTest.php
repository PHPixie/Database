<?php
namespace PHPixieTests\Database\Driver\PDO;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Query
 */
class QueryTest extends \PHPixieTests\Database\SQL\QueryTest
{
    protected $resultClass = '\PHPixie\Database\Driver\PDO\Result';

    protected function query($type = 'select')
    {
        return new \PHPixie\Database\Driver\PDO\Query($this->database, $this->conditionsMock, $this->connection, $this->parser, null, $type);
    }

    protected function mockParser()
    {
        return $this->quickMock('\PHPixie\Database\Driver\PDO\Mysql\Parser', array('parse'), array());
    }

    protected function mockConnection()
    {
        return $this->getMock('\PHPixie\Database\Driver\PDO\Connection', array('execute'), array(), '', null, false);
    }

}
