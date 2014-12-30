<?php

namespace PHPixieTests\Database\Conditions\Condition;

/**
 * @coversDefaultClass \PHPixie\Database\Conditions\Condition\Field\Operator
 */
class OperatorTest extends \PHPixieTests\Database\Conditions\Condition\ImplementationTest
{
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
        $this->assertEquals('a', $this->condition->field());
        $this->assertEquals('=', $this->condition->operator());
        $this->assertEquals(array(1), $this->condition->values());
        
        $this->assertSame($this->condition, $this->condition->setField('b'));
        $this->assertSame($this->condition, $this->condition->setOperator('>'));
        $this->assertSame($this->condition, $this->condition->setValues(array(2)));
        
        $this->assertEquals('b', $this->condition->field());
        $this->assertEquals('>', $this->condition->operator());
        $this->assertEquals(array(2), $this->condition->values());
    }
    
    protected function condition()
    {
        return new \PHPixie\Database\Conditions\Condition\Field\Operator('a', '=', array(1));
    }
}
