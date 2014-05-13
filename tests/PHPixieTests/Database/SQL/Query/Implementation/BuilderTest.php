<?php

namespace PHPixieTests\Database\SQL\Query\Implementation;

/**
 * @coversDefaultClass \PHPixie\Database\SQL\Query\Implementation\Builder
 */
class BuilderTest extends \PHPixieTests\Database\Query\Implementation\BuilderTest
{
    
    protected $builderClass = '\PHPixie\Database\SQL\Query\Implementation\Builder';
    
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
        $this->prepareJoinBuilders();
        
        $builder->addJoin('test', 'pixie', 'inner');
        $builder->addJoin('test', 'pixie', 'left');
        
        $this->assertEquals(array(
            array('builder' => $this->builders[0], 'table' => 'test', 'alias' => 'pixie', 'type' => 'inner'),
            array('builder' => $this->builders[1], 'table' => 'test', 'alias' => 'pixie', 'type' => 'left'),
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
     * @covers ::addOnCondition
     */
    public function testAddOnCondition()
    {
        $builder = $this->builder;
        $this->assertException(function() use($builder) {
            $builder->addOnCondition(array(5), 'or', true);
        });
        

        $this->prepareJoinBuilders();
        for($i=0;$i<2;$i++){
            $builder->addJoin('test', 'pixie', 'inner');
            $this->expectCalls($this->builders[$i], array('addCondition' => array('or', true, array(5))));
            $builder->addOnCondition(array(5), 'or', true);
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
        $this->prepareJoinBuilders();
        for($i=0;$i<2;$i++){
            $builder->addJoin('test', 'pixie', 'inner');
            $this->expectCalls($this->builders[$i], array('startConditionGroup' => array('or', true)));
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
        
        $this->prepareJoinBuilders();
        for($i=0;$i<2;$i++){
            $builder->addJoin('test', 'pixie', 'inner');
            $this->expectCalls($this->builders[$i], array('endGroup' => array()));
            $builder->endOnConditionGroup();
        }
    }
    
    protected function prepareJoinBuilders()
    {
        for($i=0;$i<2;$i++){
            $this->conditionsMock
                ->expects($this->at($i))
                ->method('builder')
                ->with('=*')
                ->will($this->returnValue($this->builders[$i]));
        }
    }
}