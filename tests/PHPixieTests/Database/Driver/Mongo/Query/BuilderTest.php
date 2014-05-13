<?php

namespace PHPixieTests\Database\Driver\Mongo\Query;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Query\Builder
 */
class BuilderTest extends \PHPixieTests\Database\Query\Implementation\BuilderTest
{
    
    protected $builderClass = '\PHPixie\Database\Driver\Mongo\Query\Builder';
    
    /**
     * @covers ::<protected>
     * @covers ::setCollection
     */
    public function testSetCollection()
    {
        $this->builder->setCollection('pixie');
        $this->assertEquals('pixie', $this->builder->getValue('collection'));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::addUnset
     */
    public function testAddUnset()
    {
        $builder = $this->builder;
        $builder->addUnset(array('test'));
        $builder->addUnset(array(array('pixie', 'test')));
        $this->assertEquals(array('test', 'pixie'), $builder->getArray('unset'));
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
        $this->builder->setBatchData(array(
            array('t' => 1),
            array('t' => 2)
        ));
        
        $this->assertEquals(array(
            array('t' => 1),
            array('t' => 2)
        ), $this->builder->getValue('batchData'));
    }
}