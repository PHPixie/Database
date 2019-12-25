<?php
namespace PHPixie\Tests\Database\Driver\PDO;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Query
 */
abstract class QueryTest extends \PHPixie\Tests\Database\Type\SQL\Query\ImplementationTest
{
    protected $queryClass;
    protected $resultClass = '\PHPixie\Database\Driver\PDO\Result';

    protected function getParser()
    {
        return $this->quickMock('\PHPixie\Database\Driver\PDO\Mysql\Parser', array('parse'), array());
    }

    protected function getConnection()
    {
        return $this->quickMock('\PHPixie\Database\Driver\PDO\Connection', array('execute'));
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
