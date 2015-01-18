<?php

namespace PHPixieTests\Database\Conditions\Condition\Field;

/**
 * @coversDefaultClass \PHPixie\Database\Conditions\Condition\Field\Operator
 */
class OperatorTest extends \PHPixieTests\Database\Conditions\Condition\ImplementationTest
{
    protected $field = 'a';
    protected $operator = '=';
    protected $values = array(1);
     
    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        
    }
     
    /**
     * @covers ::field
     * @covers ::setField
     * @covers ::operator
     * @covers ::setOperator
     * @covers ::values
     * @covers ::setValues
     */
    public function testProperties()
    {
        $this->assertEquals($this->field, $this->condition->field());
        $this->assertEquals($this->operator, $this->condition->operator());
        $this->assertEquals($this->values, $this->condition->values());
        
        $this->assertSame($this->condition, $this->condition->setField('b'));
        $this->assertSame($this->condition, $this->condition->setOperator('>'));
        $this->assertSame($this->condition, $this->condition->setValues(array(2)));
        
        $this->assertEquals('b', $this->condition->field());
        $this->assertEquals('>', $this->condition->operator());
        $this->assertEquals(array(2), $this->condition->values());
    }
    
    protected function condition()
    {
        return new \PHPixie\Database\Conditions\Condition\Field\Operator(
            $this->field,
            $this->operator,
            $this->values
        );
    }
}
