<?php
namespace PHPixieTests\Database\Driver\PDO;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Query
 */
abstract class QueryTest extends \PHPixieTests\Database\Type\SQL\Query\ImplementationTest
{
    protected $queryClass;
    protected $resultClass = '\PHPixie\Database\Driver\PDO\Result';

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
        return $this->getMockBuilder('\PHPixie\Database\Driver\PDO\Query\Builder')
        ->disableOriginalConstructor(true)
        ->getMock();
    }
    
    protected function query()
    {
        $class = $this->queryClass;
        return new $class($this->connection, $this->parser, $this->builder);
    }
}
