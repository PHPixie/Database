<?php
namespace PHPixieTests\Database\Driver\PDO\Query;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Query\Implementation
 */
class ImplementationTest extends \PHPixieTests\Database\SQL\Query\ImplementationTest
{
    protected $resultClass = '\PHPixie\Database\Driver\PDO\Result';

    protected function query($type = 'select')
    {
        return new \PHPixie\Database\Driver\PDO\Query($this->connection, $this->parser, null, $type);
    }

    protected function getParser()
    {
        return $this->quickMock('\PHPixie\Database\Driver\PDO\Mysql\Parser', array('parse'), array());
    }

    protected function getConnection()
    {
        return $this->getMock('\PHPixie\Database\Driver\PDO\Connection', array('execute'), array(), '', null, false);
    }
    
    protected function getBuilder()
    {
        return $this->getMock('\PHPixie\Database\Driver\PDO\Query\Implementation\Builder', array('execute'), array(), '', null, false);
    }
}
