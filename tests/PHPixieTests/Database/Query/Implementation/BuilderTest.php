<?php
namespace PHPixieTests\Database\Query\Implementation;

/**
 * @coversDefaultClass \PHPixie\Database\Query\Implementation\Builder
 */
class BuilderTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $conditionsMock;
    protected $builders;
    protected $builder;
    protected $builderClass = '\PHPixie\Database\Query\Implementation\Builder';
    
    public function setUp()
    {
        $this->builders = array(
            $this->quickMock('\PHPixie\Database\Conditions\Builder', array()),
            $this->quickMock('\PHPixie\Database\Conditions\Builder', array()),
        );
        
        $this->conditionsMock = $this->quickMock('\PHPixie\Database\Conditions', array('builder'));
        $this->builder = $this->builder();
    }
    
    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::<protected>
     * @covers ::addFields
     */
    public function testAddFields()
    {
        $builder = $this->builder;
        $builder->addFields(array('test'));
        $builder->addFields(array(array('pixie', 'fairy' => 'test')));
        $this->assertEquals(array(
            'test',
            'pixie',
        ), $builder->getArray('fields'));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::setOffset
     */
    public function testSetOffset()
    {
        $builder = $this->builder;
        $builder->setOffset(6);
        
        $this->assertException(function() use($builder){
            $builder->setOffset('t');
        });
        
        $this->assertEquals(6, $builder->getValue('offset'));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::setLimit
     */
    public function testSetLimit()
    {
        $builder = $this->builder;
        $builder->setLimit(6);
        
        $this->assertException(function() use($builder){
            $builder->setLimit('t');
        });
        
        $this->assertEquals(6, $builder->getValue('limit'));
    }

    /**
     * @covers ::<protected>
     * @covers ::addOrderAscendingBy
     * @covers ::addOrderDescendingBy
     */
    public function testOrderBy()
    {
        $builder = $this->builder;
        $builder->addOrderAscendingBy('test');
        $builder->addOrderAscendingBy('pixie');
        $builder->addOrderDescendingBy('trixie');
        $builder->addOrderDescendingBy('test');
        
        $this->assertEquals(array(
            array('field' => 'test', 'dir' => 'asc'),
            array('field' => 'pixie', 'dir' => 'asc'),
            array('field' => 'trixie', 'dir' => 'desc'),
            array('field' => 'test', 'dir' => 'desc'),
        ), $builder->getArray('orderBy'));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::addSet
     */
    public function testAddSet()
    {
        $builder = $this->builder;
        $builder->addSet(array('test', 'pixie'));
        $builder->addSet(array(array('trixie' => 'fairy', 'test2' => 5)));
        $builder->addSet(array('test2', 6));
        
        $this->assertException(function() use($builder){
            $builder->addSet(array('t'));
        });
        
        $this->assertException(function() use($builder){
            $builder->addSet(array(array('t')));
        });
        
        $this->assertException(function() use($builder){
            $builder->addSet('t');
        });
        
        $this->assertEquals(array(
            'test'   => 'pixie',
            'trixie' => 'fairy',
            'test2'  => 6
        ), $builder->getArray('set'));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::setData
     */
    public function testSetData()
    {
        $builder = $this->builder;
        $builder->setData(array('f' => 1));
        
        $this->assertException(function() use($builder){
            $builder->setData('t');
        });
        
        $this->assertEquals(array('f' => 1), $builder->getValue('data'));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::clearValue
     * @covers ::getValue
     */
    public function testGetClearValue()
    {
        $this->assertEquals(null, $this->builder->getValue('limit'));
        $this->builder->setLimit(5);
        $this->assertEquals(5, $this->builder->getValue('limit'));
        $this->builder->clearValue('limit');
        $this->assertEquals(null, $this->builder->getValue('limit'));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::clearArray
     * @covers ::getArray
     */
    public function testGetClearArray()
    {
        $this->assertEquals(array(), $this->builder->getArray('fields'));
        $this->builder->addFields(array('test'));
        $this->assertEquals(array('test'), $this->builder->getArray('fields'));
        $this->builder->clearArray('fields');
        $this->assertEquals(array(), $this->builder->getArray('fields'));
    }
    
    /**
     * @covers ::conditionBuilder
     */
    public function testConditionBuilder()
    {
        $this->prepareBuilder();
        $this
            ->conditionsMock
            ->expects($this->at(1))
            ->method('builder')
            ->will($this->returnValue($this->builders[1]));
        
        $firstBuilder = $this->builder->conditionBuilder('first');
        $secondBuilder = $this->builder->conditionBuilder('second');
        $this->assertEquals($firstBuilder, $this->builder->conditionBuilder('first'));
        $this->assertEquals($firstBuilder, $this->builder->conditionBuilder());
        $this->assertEquals($secondBuilder, $this->builder->conditionBuilder('second'));
        $this->assertEquals($secondBuilder, $this->builder->conditionBuilder());
        $this->assertNotSame($firstBuilder, $secondBuilder);
    }
    
    /**
     * @covers ::conditionBuilder
     */
    public function testConditionBuilderException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->builder->conditionBuilder();
    }
    
    /**
     * @covers ::getConditions
     */
    public function testGetConditions()
    {
        $this->prepareBuilder();
        $this->assertEquals(array(), $this->builder->getConditions('first'));
        
        $firstBuilder = $this->builder->conditionBuilder('first');
        
        $firstBuilder
            ->expects($this->at(0))
            ->method('getConditions')
            ->will($this->returnValue(array()));
                           
        $this->assertEquals(array(), $this->builder->getConditions('first'));
                        
        $firstBuilder
            ->expects($this->at(0))
            ->method('getConditions')
            ->will($this->returnValue(array(1)));
                           
        $this->assertEquals(array(1), $this->builder->getConditions('first'));
        
    }
    
    /**
     * @covers ::addCondition
     */
    public function testAddCondition()
    {
        $this->prepareBuilder();
        $this->expectCalls($this->builders[0], array('addCondition' => array('or', true, array(5))));
        $this->builder->addCondition(array(5), 'or', true, 'first');
    }
    
    /**
     * @covers ::startConditionGroup
     */
    public function testStartConditionGroup()
    {
        $this->prepareBuilder();
        $this->expectCalls($this->builders[0], array('startConditionGroup' => array('or', true)));
        $this->builder->startConditionGroup('or', true, 'first');
    }
    
    /**
     * @covers ::endConditionGroup
     */
    public function testEndConditionGroup()
    {
        $this->prepareBuilder();
        $this->expectCalls($this->builders[0], array('endGroup' => array()));
        $this->builder->endConditionGroup('first');
    }
    
    /**
     * @covers ::assert
     */
    public function testAssert()
    {
        $this->builder->assert(true, 'test');
        try{
            $this->builder->assert(false, 'test');
        }catch(\PHPixie\Database\Exception\Builder $e){
            $this->assertEquals('test', $e->getMessage());
        }
    }
    
    protected function assertException($callback)
    {
        $except = false;
        try{
            $callback();
        }catch(\PHPixie\Database\Exception\Builder $e){
            $except = true;
        }
        
        $this->assertEquals(true, $except);
    }
    
    protected function prepareBuilder()
    {
        $this->conditionsMock
                ->expects($this->at(0))
                ->method('builder')
                ->will($this->returnValue($this->builders[0]));
    }
    
    protected function builder()
    {
        $class = $this->builderClass;
        return new $class($this->conditionsMock);
    }
    
    protected function getDriver()
    {
        return $this->quickMock('\PHPixie\Database\Driver', array('valuesData'));
    }
}