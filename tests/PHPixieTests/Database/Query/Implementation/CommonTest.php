<?php
namespace PHPixieTests\Database\Query\Implementation;

/**
 * @coversDefaultClass \PHPixie\Database\Query\Implementation\Common
 */
class CommonTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $conditionsMock;
    protected $builders;
    protected $common;
    
    public function setUp()
    {
        $this->builders = array(
            $this->quickMock('\PHPixie\Database\Conditions\Builder', array()),
            $this->quickMock('\PHPixie\Database\Conditions\Builder', array()),
        );
        
        $this->conditionsMock = $this->quickMock('\PHPixie\Database\Conditions', array('builder'));
        $this->conditionsMock
                ->expects($this->any())
                ->method('builder')
                ->will($this->returnCallback(function () {
                    return $this->builder;
                }));
        
        $this->common = $this->buildCommon();
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
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->query->fields('test');    
    }
    
    /**
     * @covers ::offset
     * @covers ::getOffset
     */
    public function testOffset()
    {
        $this->getSetTest('offset', 5);
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->query->offset('test');
    }

    /**
     * @covers ::limit
     * @covers ::getLimit
     */
    public function testLimit()
    {
        $this->getSetTest('limit', 5);
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->query->limit('test');
    }

    /**
     * @covers ::orderAscendingBy
     * @covers ::orderDescendingBy
     * @covers ::getOrderBy
     */
    public function testOrderBy()
    {
        $this->assertEquals(array(), $this->query->getOrderBy());
        $this->assertEquals($this->query, $this->query->orderDescendingBy('id'));
        $this->assertEquals(array(array('id', 'desc')), $this->query->getOrderBy());
        $this->assertEquals($this->query, $this->query->orderAscendingBy('name'));
        $this->assertEquals(array(array('id','desc'),array('name','asc')), $this->query->getOrderBy());
    }

    /**
     * @covers ::data
     */
    public abstract function testData();
    
    
    /**
     * @covers ::conditionBuilder
     */
    public function testConditionBuilder()
    {
        $this
            ->conditionsMock
            ->expects($this->at(0))
            ->method('builder')
            ->will($this->returnValue($this->builders[0]));
        
        $this
            ->conditionsMock
            ->expects($this->at(1))
            ->method('builder')
            ->will($this->returnValue($this->builders[1]));
        
        $firstBuilder = $this->common->conditionBuilder('first');
        $secondBuilder = $this->common->conditionBuilder('second');
        $this->assertEquals($firstBuilder, $this->common->conditionBuilder('first'));
        $this->assertEquals($firstBuilder, $this->common->conditionBuilder());
        $this->assertEquals($secondBuilder, $this->common->conditionBuilder('second'));
        $this->assertEquals($secondBuilder, $this->common->conditionBuilder());
        $this->assertNotEquals($firstBuilder, $secondBuilder);
    }
    
    /**
     * @covers ::conditionBuilder
     */
    public function testConditionBuilderException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->common->conditionBuilder();
    }
    
    /**
     * @covers ::getConditions
     */
    public function testGetConditions()
    {
        $this->prepareBuilder();
        $this->assertEquals(array(), $this->common->getConditions('first'));
        
        $firstBuilder = $this->common->conditionBuilder('first');
        
        $firstBuilder
            ->expects($this->at(0))
            ->method('getConditions')
            ->will($this->returnValue(array()));
                           
        $this->assertEquals(array(), $this->common->getConditions('first'));
                        
        $firstBuilder
            ->expects($this->at(0))
            ->method('getConditions')
            ->will($this->returnValue(array(1)));
                           
        $this->assertEquals(array(1), $this->common->getConditions('first'));
        
    }
    
    /**
     * @covers ::addCondition
     */
    public function testAddCondition()
    {
        $this->prepareBuilder();
        $this->expectCalls($this->builders[0], array('addCondition' => array('or', true, array(5))));
        $this->common->addCondition(array(5), 'or', true, 'first');
    }
    
    /**
     * @covers ::startConditionGroup
     */
    public function testStartConditionGroup()
    {
        $this->prepareBuilder();
        $this->expectCalls($this->builders[0], array('startConditionGroup' => array('or', true)));
        $this->common->startConditionGroup('or', true, 'first');
    }
    
    /**
     * @covers ::startConditionGroup
     */
    public function testStartConditionGroup()
    {
        $this->prepareBuilder();
        $this->expectCalls($this->builders[0], array('startConditionGroup' => array('or', true)));
        $this->common->startConditionGroup('or', true, 'first');
    }
    
    /**
     * @covers ::endConditionGroup
     */
    public function testEndConditionGroup()
    {
        $this->prepareBuilder();
        $this->expectCalls($this->builders[0], array('endConditionGroup' => array()));
        $this->common->endConditionGroup('first');
    }
    
    /**
     * @covers ::assert
     */
    public function testAssert()
    {
        $this->common->assert(true, 'test');
        try{
            $this->assert(false, 'test');
        }catch(\PHPixie\Database\Exception\Builder $e){
            $this->assertEquals('test', $e->getMessage());
        }
    }
    
    protected function prepareBuilder()
    {
        $this->expectCalls($this->conditionsMock, array(
                                                    'getConditions' => array('first')
                                                ), array(
                                                    'getConditions' => $this->builders[0]
                                                ));
    }
    
    protected function getSetTest($method, $param, $default = null)
    {
        $this->assertEquals($default, call_user_func_array(array($this->query, 'get'.ucfirst($method)), array()));
        $this->assertEquals($this->query, call_user_func_array(array($this->query, $method), array($param)));
        $this->assertEquals($param, call_user_func_array(array($this->query, 'get'.ucfirst($method)), array()));
    }
    
    protected function buildCommon()
    {
        return new \PHPixie\Database\Query\Implementation\Common($this->conditionsMock);
    }
}