<?php
namespace PHPixieTests\DB;

class BuilderStub
{
    public $passed = array();
    public $startGroupLogic;
    public $endGroupCalled = false;

    public function addCondition()
    {
        $this->passed[] = func_get_args();
    }

    public function getConditions()
    {
        return array(1);
    }

    public function startGroup($logic)
    {
        $this->startGroupLogic = $logic;
    }

    public function endGroup()
    {
        $this->endGroupCalled = true;
    }
}

/**
 * @coversDefaultClass \PHPixie\DB\Query
 */
abstract class QueryTest extends \PHPUnit_Framework_TestCase
{
    protected $conditionsMock;
    protected $db;
    protected $query;
    protected $parser;
    protected $connection;
    protected $builder;

    protected function setUp()
    {
        $this->conditionsMock = $this->getMock('\PHPixie\DB\Conditions', array('builder'));
        $this->conditionsMock
                ->expects($this->any())
                ->method('builder')
                ->will($this->returnCallback(function () {
                    return $this->builder;
                }));
        
        $this->db = $this->getMock('\PHPixie\DB', array('conditions'), array(null));
        $this->db
                ->expects($this->any())
                ->method('conditions')
                ->will($this->returnValue($this->conditionsMock));
        
        $this->parser = $this->mockParser();
        $this->query = $this->query();
        $this->builder = new BuilderStub();
    }
    
    /**
     * @covers ::data
     * @covers ::getData
     */
    public function testData()
    {
        $this->getSetTest('data', array('a' =>1));
    }
    
    /**
     * @covers ::type
     * @covers ::getType
     */
    public function testType()
    {
        $this->assertEquals('select', $this->query->getType());
        $this->assertEquals($this->query, $this->query->type('delete'));
        $this->assertEquals('delete', $this->query->getType());
    }
    
    /**
     * @covers ::fields
     * @covers ::getFields
     */
    public function testFields()
    {
        $this->assertEquals(array(), $this->query->getFields());
        $this->assertEquals($this->query, $this->query->fields(array('id')));
        $this->assertEquals(array('id'), $this->query->getFields());
        $this->assertBuilderException(function () {
            $this->query->fields('test');
        });
    }
    
    /**
     * @covers ::offset
     * @covers ::getOffset
     */
    public function testOffset()
    {
        $this->getSetTest('offset', 5);
        $this->assertBuilderException(function () {
            $this->query->offset('test');
        });
    }
    
    /**
     * @covers ::limit
     * @covers ::getLimit
     */
    public function testLimit()
    {
        $this->getSetTest('limit', 5);
        $this->assertBuilderException(function () {
            $this->query->limit('test');
        });
    }
    
    /**
     * @covers ::orderBy
     * @covers ::getOrderBy
     */
    public function testOrderBy()
    {
        $this->assertEquals(array(), $this->query->getOrderBy());
        $this->assertEquals($this->query, $this->query->orderBy('id', 'desc'));
        $this->assertEquals(array(array('id', 'desc')), $this->query->getOrderBy());
        $this->assertEquals($this->query, $this->query->orderBy('name'));
        $this->assertEquals(array(array('id','desc'),array('name','asc')), $this->query->getOrderBy());
        $this->assertBuilderException(function () {
            $this->query->orderBy('name', 'test');
        });
    }
    
    /**
     * @covers ::where
     * @covers ::orWhere
     * @covers ::xorWhere
     * @covers ::andWhereNot
     * @covers ::orWhereNot
     * @covers ::xorWhereNot
     * @covers ::startWhereGroup
     * @covers ::endWhereGroup
     */
    public function testWhere()
    {
        $this->builderTest('where');
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

    /**
     * @covers ::_and
     * @covers ::_or
     * @covers ::_xor
     * @covers ::_andNot
     * @covers ::_orNot
     * @covers ::_xorNot
     * @covers ::startGroup
     * @covers ::endGroup
     */
    public function testGenericBuilder()
    {
        $this->assertBuilderException(function () {
            $this->query->_and('a', 1);
        });
        $this->assertBuilderException(function () {
            $this->query->startGroup();
        });
        $this->query->where('a', 1);
        $this->genericBuilderTest($this->builder);
    }
    
    protected function getSetTest($method, $param, $default = null)
    {
        $this->assertEquals($default, call_user_func_array(array($this->query, 'get'.ucfirst($method)), array()));
        $this->assertEquals($this->query, call_user_func_array(array($this->query, $method), array($param)));
        $this->assertEquals($param, call_user_func_array(array($this->query, 'get'.ucfirst($method)), array()));
    }

    protected function builderTest($name, $testGet = true)
    {
        $name = ucfirst($name);
        $this->assertEquals($this->query, call_user_func(array($this->query, $name), 'id', 1));
        $this->assertEquals(array('and', false, array('id', 1)), end($this->builder->passed));

        $this->assertEquals($this->query, call_user_func(array($this->query, 'or'.$name), 'id', 1));
        $this->assertEquals(array('or', false, array('id', 1)), end($this->builder->passed));

        $this->assertEquals($this->query, call_user_func(array($this->query, 'xor'.$name), 'id', 1));
        $this->assertEquals(array('xor', false, array('id', 1)), end($this->builder->passed));

        $this->assertEquals($this->query, call_user_func(array($this->query, $name.'Not'), 'id', 1));
        $this->assertEquals(array('and', true, array('id', 1)), end($this->builder->passed));

        $this->assertEquals($this->query, call_user_func(array($this->query, 'or'.$name.'Not'), 'id', 1));
        $this->assertEquals(array('or', true, array('id', 1)), end($this->builder->passed));

        $this->assertEquals($this->query, call_user_func(array($this->query, 'xor'.$name.'Not'), 'id', 1));
        $this->assertEquals(array('xor', true, array('id', 1)), end($this->builder->passed));

        $this->assertEquals($this->query, call_user_func(array($this->query, 'start'.$name.'Group')));
        $this->assertEquals($this->query, call_user_func(array($this->query, 'end'.$name.'Group')));
        $this->assertEquals('and', $this->builder->startGroupLogic);
        $this->assertEquals(true, $this->builder->endGroupCalled);

        $this->builder->startGroupLogic = null;
        $this->builder->endGroupCalled = false;

        $this->assertEquals($this->query, call_user_func(array($this->query, 'start'.$name.'Group'), 'or'));
        $this->assertEquals($this->query, call_user_func(array($this->query, 'end'.$name.'Group')));
        $this->assertEquals('or', $this->builder->startGroupLogic);
        $this->assertEquals(true, $this->builder->endGroupCalled);

        if($testGet)
            $this->assertEquals(array(1), $this->query->getConditions($name));

    }

    protected function genericBuilderTest($builder)
    {
        $this->assertEquals($this->query, $this->query->_and('id', 1));
        $this->assertEquals(array('and', false, array('id', 1)), end($builder->passed));

        $this->assertEquals($this->query, $this->query->_or('id', 1));
        $this->assertEquals(array('or', false, array('id', 1)), end($builder->passed));

        $this->assertEquals($this->query, $this->query->_xor('id', 1));
        $this->assertEquals(array('xor', false, array('id', 1)), end($builder->passed));

        $this->assertEquals($this->query, $this->query->_andNot('id', 1));
        $this->assertEquals(array('and', true, array('id', 1)), end($builder->passed));

        $this->assertEquals($this->query, $this->query->_orNot('id', 1));
        $this->assertEquals(array('or', true, array('id', 1)), end($builder->passed));

        $this->assertEquals($this->query, $this->query->_xorNot('id', 1));
        $this->assertEquals(array('xor', true, array('id', 1)), end($builder->passed));

        $this->assertEquals($this->query, call_user_func(array($this->query, 'startGroup'), 'or'));
        $this->assertEquals($this->query, call_user_func(array($this->query, 'endGroup')));
        $this->assertEquals('or', $builder->startGroupLogic);
        $this->assertEquals(true, $builder->endGroupCalled);
    }
    
    protected function assertBuilderException($callback) {
        $this->setExpectedException('\PHPixie\DB\Exception\Builder');
        $callback();
    }
    
    abstract public function testExecute();
    abstract protected function query();
}
