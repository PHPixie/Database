<?php
namespace PHPixieTests\Database\Type\Document\Query\Implementation;

/**
 * @coversDefaultClass \PHPixie\Database\Type\Document\Query\Implementation\Builder
 */
abstract class BuilderTest extends \PHPixieTests\Database\Query\Implementation\BuilderTest
{
    /**
     * @covers ::startSubdocumentConditionGroup
     * @covers ::<protected>
     */
    public function testStartSubdocumentConditionGroup()
    {
        $this->prepareContainer();
        $this->expectCalls($this->containers[0], array('startSubdocumentConditionGroup' => array('or', true)));
        $this->builder->startSubdocumentConditionGroup('or', true, 'first');
    }
    
    /**
     * @covers ::startSubarrayItemConditionGroup
     * @covers ::<protected>
     */
    public function testStartSubarrayItemConditionGroup()
    {
        $this->prepareContainer();
        $this->expectCalls($this->containers[0], array('startSubarrayItemConditionGroup' => array('or', true)));
        $this->builder->startSubarrayItemConditionGroup('or', true, 'first');
    }
    
    /**
     * @covers ::addSubdocumentPlaceholder
     * @covers ::<protected>
     */
    public function testAddSubdocumentPlaceholder()
    {
        $this->prepareContainer();
        $this->expectCalls($this->containers[0], array('addSubdocumentPlaceholder' => array('pixie', 'or', true, false)));
        $this->builder->addSubdocumentPlaceholder('pixie', 'or', true, false, 'first');
    }
    
    /**
     * @covers ::addSubarrayItemPlaceholder
     * @covers ::<protected>
     */
    public function testAddSubarrayItemPlaceholder()
    {
        $this->prepareContainer();
        $this->expectCalls($this->containers[0], array('addSubarrayItemPlaceholder' => array('pixie', 'or', true, false)));
        $this->builder->addSubarrayItemPlaceholder('pixie', 'or', true, false, 'first');
    }
    
    protected function conditions()
    {
        return $this->quickMock('\PHPixie\Database\Type\Document\Conditions', array('container'));
    }
    
    protected function container()
    {
        return $this->quickMock('\PHPixie\Database\Type\Document\Conditions\Builder\Container', array());
    }
    
}