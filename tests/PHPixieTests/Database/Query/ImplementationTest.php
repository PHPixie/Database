<?php
namespace PHPixieTests\Database\Query;

/**
 * @coversDefaultClass \PHPixie\Database\Query\Implementation
 */
abstract class ImplementationTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $database;
    protected $query;
    protected $parser;
    protected $builder;
    protected $type;

    protected function setUp()
    {
        $this->database = $this->getMock('\PHPixie\Database', array('conditions'), array(null));
        $this->parser = $this->mockParser();
        $this->query = $this->query();
        $this->builder = $this->builderStub();
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

    protected function getSetTest($method, $param, $default = null)
    {
        $this->assertEquals($default, call_user_func_array(array($this->query, 'get'.ucfirst($method)), array()));
        $this->assertEquals($this->query, call_user_func_array(array($this->query, $method), array($param)));
        $this->assertEquals($param, call_user_func_array(array($this->query, 'get'.ucfirst($method)), array()));
    }


    protected function builderStub()
    {
        return new BuilderStub();
    }
    abstract public function testExecute();
    abstract protected function query();
}
