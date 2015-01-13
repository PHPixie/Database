<?php

namespace PHPixieTests\Database\Type\Document;

/**
 * @coversDefaultClass \PHPixie\Database\Type\Document\Conditions
 */
abstract class ConditionsTest extends \PHPixieTests\Database\ConditionsTest
{
    /**
     * @covers ::subdocumentGroup
     * @covers ::<protected>
     */
    public function testSubdocumentGroup()
    {
        $condition = $this->conditions->subdocumentGroup('test');
        $this->assertInstanceOf('\PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded\Group\Subdocument', $condition);
        $this->assertSame('test', $condition->field());
    }
    
    /**
     * @covers ::subarrayItemGroup
     * @covers ::<protected>
     */
    public function testSubarrayItemGroup()
    {
        $condition = $this->conditions->subarrayItemGroup('test');
        $this->assertInstanceOf('\PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded\Group\SubarrayItem', $condition);
        $this->assertSame('test', $condition->field());
    }
    
    /**
     * @covers ::placeholder
     * @covers ::<protected>
     */
    public function testPlaceholder()
    {
        $placeholder = $this->conditions->placeholder();
        $this->assertInstanceOf('\PHPixie\Database\Type\Document\Conditions\Condition\Collection\Placeholder', $placeholder);
        $this->assertPlaceholderContainer($placeholder, '=', true);
        
        $placeholder = $this->conditions->placeholder('>', false);
        $this->assertPlaceholderContainer($placeholder, '>', false);
        
    }
    
    /**
     * @covers ::subdocumentPlaceholder
     * @covers ::<protected>
     */
    public function testSubdocumentPlaceholder()
    {
        $condition = $this->conditions->subdocumentPlaceholder('test');
        $this->assertInstanceOf('\PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded\Placeholder\Subdocument', $condition);
        $this->assertSame('test', $condition->field());
        $this->assertPlaceholderContainer($condition, '=', true);
        
        $condition = $this->conditions->subdocumentPlaceholder('test', '>', false);
        $this->assertPlaceholderContainer($condition, '>', false);
    }
    
    /**
     * @covers ::subarrayItemPlaceholder
     * @covers ::<protected>
     */
    public function testSubarrayItemPlaceholder()
    {
        $condition = $this->conditions->subarrayItemPlaceholder('test');
        $this->assertInstanceOf('\PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded\Placeholder\SubarrayItem', $condition);
        $this->assertSame('test', $condition->field());
        $this->assertPlaceholderContainer($condition, '=', true);
        
        $condition = $this->conditions->subarrayItemPlaceholder('test', '>', false);
        $this->assertPlaceholderContainer($condition, '>', false);
    }
    
    protected function assertPlaceholderContainer($placeholder, $defaultOperator, $allowEmpty)
    {
        $this->assertInstanceOf('\PHPixie\Database\Type\Document\Conditions\Builder\Container', $placeholder->container());
        $this->assertAttributeSame($defaultOperator, 'defaultOperator', $placeholder->container());
        $this->assertAttributeSame($allowEmpty, 'allowEmpty', $placeholder);
    }
}
