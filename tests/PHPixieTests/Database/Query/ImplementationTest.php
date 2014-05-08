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

    protected function testBuilderMethod($method, $with, $will = null, $at=null, $builderWill = null, $builderWith = null, $builderMethod = null)
    {
        if($builderWith === null)
            $builderWith = $with;

        if($builderMethod === null)
            $builderMethod = $method;
        
        if($builderMethod === null)
            $builderMethod = $method;
        
        $methodMock = $this->builder;
        
        if($at!==null)
            $methodMock = $methodMock->expects($this->at($at));
        
        $methodMock = $methodMock->method($method);
        
        $methodMock = call_user_func_array(array($methodMock, 'with'), $builderWith);
        $methodMock->will($this->returnValue($builderWill));
        
        $result = call_user_func_array(array($this->query, $method), $with);
        $this->acceptEquals($will, $result);
    }

    abstract public function testExecute();
    abstract protected function getConnection();
    abstract protected function getParser();
    abstract protected function getBuilder();
    abstract protected function query();
}
