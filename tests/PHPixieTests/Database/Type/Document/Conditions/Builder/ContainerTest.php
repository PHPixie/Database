<?php

namespace PHPixieTests\Database\Type\Document\Conditions\Builder;

/**
 * @coversDefaultClass \PHPixie\Database\Type\Document\Conditions\Builder\Container
 */
class ContainerTest extends \PHPixieTests\Database\Conditions\Builder\ContainerTest
{
    protected $databaseConditions;
    
    /**
     * @covers ::startSubdocumentConditionGroup
     * @covers ::startSubdocumentGroup
     * @covers ::startAndSubdocumentGroup
     * @covers ::startOrSubdocumentGroup
     * @covers ::startXorSubdocumentGroup
     * @covers ::startNotSubdocumentGroup
     * @covers ::startAndNotSubdocumentGroup
     * @covers ::startOrNotSubdocumentGroup
     * @covers ::startXorNotSubdocumentGroup
     * @covers ::<protected>
     */
    public function testSubdocumentConditions()
    {
        $this->embeddedGroupTest('subdocument');
    }
    
    /**
     * @covers ::startSubarrayItemConditionGroup
     * @covers ::startSubarrayItemGroup
     * @covers ::startAndSubarrayItemGroup
     * @covers ::startOrSubarrayItemGroup
     * @covers ::startXorSubarrayItemGroup
     * @covers ::startNotSubarrayItemGroup
     * @covers ::startAndNotSubarrayItemGroup
     * @covers ::startOrNotSubarrayItemGroup
     * @covers ::startXorNotSubarrayItemGroup
     * @covers ::<protected>
     */
    public function testSubarrayItemConditions()
    {
        $this->embeddedGroupTest('subarrayItem');
    }
    
    protected function embeddedGroupTest($type)
    {
        $uType = ucfirst($type);
        $this->container()
            ->{'start'.$uType.'ConditionGroup'}('pixie', 'or', true)->endGroup()
            ->{'start'.$uType.'Group'}('pixie')->endGroup()
            ->{'startAnd'.$uType.'Group'}('pixie')->endGroup()
            ->{'startOr'.$uType.'Group'}('pixie')->endGroup()
            ->{'startXor'.$uType.'Group'}('pixie')->endGroup()
            ->{'startNot'.$uType.'Group'}('pixie')->endGroup()
            ->{'startAndNot'.$uType.'Group'}('pixie')->endGroup()
            ->{'startOrNot'.$uType.'Group'}('pixie')->endGroup()
            ->{'startXorNot'.$uType.'Group'}('pixie')->endGroup();

        $this->assertConditions(array(
            array('or', true, $type, 'pixie', array()),
            array('and', false, $type, 'pixie', array()),
            array('and', false, $type, 'pixie', array()),
            array('or',  false, $type, 'pixie', array()),
            array('xor', false, $type, 'pixie', array()),
            array('and', true, $type, 'pixie', array()),
            array('and', true, $type, 'pixie', array()),
            array('or',  true, $type, 'pixie', array()),
            array('xor', true, $type, 'pixie', array()),
        ));
    }
    
    /**
     * @covers ::addPlaceholder
     * @covers ::<protected>
     */
    public function testPlaceholderType()
    {
        $this->container->addPlaceholder();
        $placeholder = $this->getLastCondition();
        
        $this->assertInstanceOf('\PHPixie\Database\Type\Document\Conditions\Condition\Collection\Placeholder', $placeholder);
    }
    
    /**
     * @covers ::addSubdocumentPlaceholder
     * @covers ::<protected>
     */
    public function testAddSubdocumentCondition()
    {
        $container = $this->container->addSubdocumentPlaceholder('pixie');
        $this->assertSubdocument(false, $container, 'pixie', 'and', false, true);

        $container = $this->container->addSubdocumentPlaceholder('pixie', 'or', true, false);
        $this->assertSubdocument(false, $container, 'pixie', 'or', true, false);
    }
    
    /**
     * @covers ::addSubarrayItemPlaceholder
     * @covers ::<protected>
     */
    public function testAddSubarrayItemCondition()
    {
        $container = $this->container->addSubarrayItemPlaceholder('pixie');
        $this->assertSubdocument(true, $container, 'pixie', 'and', false, true);

        $container = $this->container->addSubarrayItemPlaceholder('pixie', 'or', true, false);
        $this->assertSubdocument(true, $container, 'pixie', 'or', true, false);
    }
    
    protected function assertSubdocument($isArray, $container, $field, $logic, $negated, $allowEmpty)
    {
        if($isArray) {
            $class = '\PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded\Placeholder\SubarrayItem';
        }else{
            $class = '\PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded\Placeholder\Subdocument';
        }
        
        $subdocument = $this->getLastCondition();
        $this->assertInstanceOf($class, $subdocument);
        $this->assertEquals('pixie', $subdocument->field());
        $this->assertPlaceholder($container, $logic, $negated, $allowEmpty);
    }
    
    protected function assertCondition($condition, $expected)
    {
        if ($condition instanceof \PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded\Group) {
            if($condition instanceof \PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded\Group\Subdocument) {
                $type = 'subdocument';
            }else{
                $type = 'subarrayItem';
            }
            $this->assertEquals($expected[2], $type);
            $this->assertEquals($expected[3], $condition->field());
            $this->assertConditionArray($condition->conditions(), $expected[4]);
        } else {
            parent::assertCondition($condition, $expected);
        }
    }
    
    protected function container($defaultOperator = '=')
    {
        if($this->databaseConditions === null) {
            $this->databaseConditions = new \PHPixie\Database\Type\Document\Conditions($this->conditions);
        }
        
        return new \PHPixie\Database\Type\Document\Conditions\Builder\Container(
            $this->conditions,
            $this->databaseConditions,
            $defaultOperator
        );
    }
}