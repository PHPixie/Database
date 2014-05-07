<?php
namespace PHPixieTests\Database\Query;

/**
 * @coversDefaultClass \PHPixie\Database\Query\Implementation
 */
abstract class ImplementationTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $connection;
    protected $parser;
    protected $builder;
    protected $query;
    protected $type;

    protected function setUp()
    {
        $this->connection = $this->getConnection();
        $this->parser = $this->getParser();
        $this->builder = $this->getBuilder();
        $this->query = $this->query();
    }

    /**
     * @covers ::type
     */
    public function testType()
    {
        $this->assertEquals($this->type, $this->query->type());
    }

 
    /**
     * @covers ::parse
     */
    public function testParse()
    {
        $query = $this->query();
        $this->parser
                ->expects($this->any())
                ->method('parse')
                ->with ($query)
                ->will($this->returnValue('a'));
        $this->assertEquals('a', $query->parse());
    }

    protected function testBuilderMethod($method, $with, $at=0, $params = null)
    {
        if($params === null)
            $params = $with;
        
        $methodMock = $this->builder
                    ->expects($this->at($at))
                    ->method($method);
        
        call_user_func_array(array($methodMock, 'with'), $with);
        call_user_func_array(array($this->query, $method), $params);
    }


    abstract public function testExecute();
    abstract protected function getConnection();
    abstract protected function getParser();
    abstract protected function getBuilder();
    abstract protected function query();
}
