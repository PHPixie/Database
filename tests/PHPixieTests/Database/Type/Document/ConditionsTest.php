<?php

namespace PHPixieTests\Database\Type\Document;

/**
 * @coversDefaultClass \PHPixie\Database\Type\Document\Conditions
 */
class ConditionsTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $databaseConditions;
    
    protected $conditions;
    
    public function setUp()
    {
        $this->databaseConditions = $this->quickMock('\PHPixie\Database\Conditions', array());
        $this->conditions = new \PHPixie\Database\Type\Document\Conditions($this->databaseConditions);
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::container
     * @covers ::<protected>
     */
    public function testContainer()
    {
        $container = $this->conditions->container();
        $this->assertInstanceOf('\PHPixie\Database\Type\Document\Conditions\Builder\Container', $container);
        $this->assertAttributeSame($this->databaseConditions, 'conditions', $container);
        $this->assertAttributeSame($this->conditions, 'documentConditions', $container);
        $this->assertAttributeSame('=', 'defaultOperator', $container);
        
        $container = $this->conditions->container('>');
        $this->assertAttributeSame('>', 'defaultOperator', $container);
    }
    
    /**
     * @covers ::placeholder
     * @covers ::<protected>
     */
    public function testPlaceholder()
    {
        $placeholder = $this->conditions->placeholder();
        $this->assertInstanceOf('\PHPixie\Database\Type\Document\Conditions\Condition\Placeholder', $placeholder);
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
        $this->assertInstanceOf('\PHPixie\Database\Type\Document\Conditions\Condition\Placeholder\Embedded\Subdocument', $condition);
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
        $this->assertInstanceOf('\PHPixie\Database\Type\Document\Conditions\Condition\Placeholder\Embedded\SubarrayItem', $condition);
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
