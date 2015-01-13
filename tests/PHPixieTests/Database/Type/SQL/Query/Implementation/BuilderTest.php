<?php

namespace PHPixieTests\Database\Type\SQL\Query\Implementation;

/**
 * @coversDefaultClass \PHPixie\Database\Type\SQL\Query\Implementation\Builder
 */
abstract class BuilderTest extends \PHPixieTests\Database\Query\Implementation\BuilderTest
{
    
    /**
     * @covers ::<protected>
     * @covers ::addFields
     */
    public function testAddFields()
    {
        $builder = $this->builder;
        $builder->addFields(array('test'));
        $builder->addFields(array('test', 'alias'));
        $builder->addFields(array(array('pixie', 'fairy' => 'trixie')));
        
        $this->assertException(function() use($builder){
            $builder->addFields(array('test', 'alias', 'test'));
        });
        
        $this->assertEquals(array(
            'test',
            'alias' => 'test',
            'pixie',
            'fairy' => 'trixie'
        ), $builder->getArray('fields'));
    }

    
    /**
     * @covers ::<protected>
     * @covers ::setTable
     */
    public function testSetTable()
    {
        $this->builder->setTable('pixie', 'test');
        $this->assertEquals(array(
            'table' => 'pixie',
            'alias' => 'test'
        ), $this->builder->getValue('table'));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::addJoin
     */
    public function testAddJoin()
    {
        $builder = $this->builder;
        $this->prepareJoinContainers();
        
        $builder->addJoin('test', 'pixie', 'inner');
        $builder->addJoin('test', 'pixie', 'left');
        
        $this->assertEquals(array(
            array('container' => $this->containers[0], 'table' => 'test', 'alias' => 'pixie', 'type' => 'inner'),
            array('container' => $this->containers[1], 'table' => 'test', 'alias' => 'pixie', 'type' => 'left'),
        ), $builder->getArray('joins'));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::addGroupBy
     */
    public function testAddGroupBy()
    {
        $builder = $this->builder;
        $builder->addGroupBy(array('test'));
        $builder->addGroupBy(array(array('pixie', 'test')));
        $this->assertEquals(array('test', 'pixie', 'test'), $builder->getArray('groupBy'));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::addUnion
     */
    public function testAddUnion()
    {
        $builder = $this->builder;
        $builder->addUnion('test', true);
        $builder->addUnion('pixie', false);
        $this->assertEquals(array(
            array('query' => 'test', 'all' => true),
            array('query' => 'pixie', 'all' => false),
        ), $builder->getArray('unions'));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::addIncrement
     */
    public function testAddIncrement()
    {
        $builder = $this->builder;
        $builder->addIncrement(array('test', 6));
        $builder->addIncrement(array(array('trixie' => 4, 'test2' => 5)));
        $builder->addIncrement(array('test2', 6));
        
        $this->assertException(function() use($builder){
            $builder->addIncrement(array('t'));
        });
        
        $this->assertException(function() use($builder){
            $builder->addIncrement(array(array('t')));
        });
        
        $this->assertException(function() use($builder){
            $builder->addIncrement('t');
        });
        
        $this->assertException(function() use($builder){
            $builder->addIncrement(array('test', 't'));
        });
        
        $this->assertEquals(array(
            'test'   => 6,
            'trixie' => 4,
            'test2'  => 6
        ), $builder->getArray('increment'));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::setBatchData
     */
    public function testSetBatchData()
    {
        $this->builder->setBatchData(array('r'), array(array(1), array(2)));
        $this->assertEquals(array(
            'columns' => array('r'),
            'rows'    => array(array(1), array(2))
        ), $this->builder->getValue('batchData'));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::buildOnCondition
     */
    public function testBuildOnCondition()
    {
        $builder = $this->builder;
        $this->assertException(function() use($builder) {
            $builder->buildOnCondition('or', true, array(5));
        });
        

        $this->prepareJoinContainers();
        for($i=0;$i<2;$i++){
            $builder->addJoin('test', 'pixie', 'inner');
            $this->expectCalls($this->containers[$i], array('buildCondition' => array('or', true, array(5))));
            $builder->buildOnCondition('or', true, array(5));
        }
    }
    
    /**
     * @covers ::<protected>
     * @covers ::addOnCondition
     */
    public function testAddOnCondition()
    {
        $builder = $this->builder;
        $condition = $this->quickMock('\PHPixie\Database\Conditions\Condition', array());
        
        $this->assertException(function() use($builder, $condition) {
            $builder->addOnCondition('or', true, $condition);
        });
        

        $this->prepareJoinContainers();
        for($i=0;$i<2;$i++){
            $builder->addJoin('test', 'pixie', 'inner');
            $this->expectCalls($this->containers[$i], array('addCondition' => array('or', true, $condition)));
            $builder->addOnCondition('or', true, $condition);
        }
    }
    
    /**
     * @covers ::<protected>
     * @covers ::startOnConditionGroup
     */
    public function testStartOnConditionGroup()
    {
        $builder = $this->builder;
        $this->assertException(function() use($builder) {
            $builder->startOnConditionGroup('or', true);
        });
        $this->prepareJoinContainers();
        for($i=0;$i<2;$i++){
            $builder->addJoin('test', 'pixie', 'inner');
            $this->expectCalls($this->containers[$i], array('startConditionGroup' => array('or', true)));
            $builder->startOnConditionGroup('or', true);
        }
    }
    
    /**
     * @covers ::<protected>
     * @covers ::endOnConditionGroup
     */
    public function testEndOnConditionGroup()
    {
        $builder = $this->builder;
        $this->assertException(function() use($builder) {
            $builder->endOnConditionGroup();
        });
        
        $this->prepareJoinContainers();
        for($i=0;$i<2;$i++){
            $builder->addJoin('test', 'pixie', 'inner');
            $this->expectCalls($this->containers[$i], array('endGroup' => array()));
            $builder->endOnConditionGroup();
        }
    }
    
    /**
     * @covers ::<protected>
     * @covers ::addOnOperatorCondition
     */
    public function testAddOnOperatorCondition()
    {
        $builder = $this->builder;
        $this->assertException(function() use($builder) {
            $builder->endOnConditionGroup();
        });

        $params = array('or', true, 'age', '>', array(5));
        $this->prepareJoinContainers();
        for($i=0;$i<2;$i++){
            $builder->addJoin('test', 'pixie', 'inner');
            $this->expectCalls($this->containers[$i], array('addOperatorCondition' => $params ));
            call_user_func_array(array($builder, 'addOnOperatorCondition'), $params);
        }
    }
    
    /**
     * @covers ::<protected>
     * @covers ::addOnPlaceholder
     */
    public function testAddOnPlaceholder()
    {
        $builder = $this->builder;
        $this->assertException(function() use($builder) {
            $builder->endOnConditionGroup();
        });

        $params = array('or', true, false);
        $this->prepareJoinContainers();
        for($i=0;$i<2;$i++){
            $builder->addJoin('test', 'pixie', 'inner');
            $this->expectCalls($this->containers[$i], array('addPlaceholder' => $params ));
            call_user_func_array(array($builder, 'addOnPlaceholder'), $params);
        }
    }
    
    /**
     * @covers ::addInOperatorCondition
     */
    public function testAddInOperatorCondition()
    {
        $this->prepareContainer();
        
        $this->expectCalls($this->containers[0], array(
            'addInOperatorCondition' => array('pixie',array(5), 'or', true)
        ));
        $this->builder->addInOperatorCondition('pixie',array(5), 'or', true, 'first');
    }
    
    /**
     * @covers ::addOnInOperatorCondition
     */
    public function testAddOnInOperatorCondition()
    {
        $builder = $this->builder;
        $this->assertException(function() use($builder) {
            $builder->addOnInOperatorCondition('pixie', array(), 'or', true);
        });
        
        $params = array('pixie',array(5), 'or', true);
        $this->prepareJoinContainers();
        for($i=0;$i<2;$i++){
            $builder->addJoin('test', 'pixie', 'inner');
            $this->expectCalls($this->containers[$i], array(
                'addInOperatorCondition' => array('pixie', array(5), 'or', true)
            ));
            call_user_func_array(array($builder, 'addOnInOperatorCondition'), $params);
        }    }
    
    protected function prepareJoinContainers()
    {
        for($i=0;$i<2;$i++){
            $this->conditionsMock
                ->expects($this->at($i))
                ->method('container')
                ->with('=*')
                ->will($this->returnValue($this->containers[$i]));
        }
    }
}