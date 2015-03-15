<?php

namespace PHPixie\Tests\Database\Conditions\Condition\Field;

/**
 * @coversDefaultClass \PHPixie\Database\Conditions\Condition\Field\Implementation
 */
abstract class ImplementationTest extends \PHPixie\Tests\Database\Conditions\Condition\ImplementationTest
{
    protected $field = 'a';
     
    /**
     * @covers \PHPixie\Database\Conditions\Condition\Field\Implementation::__construct
     * @covers ::__construct
     */
    public function testConstruct()
    {
        
    }
     
    /**
     * @covers ::field
     * @covers ::setField
     */
    public function testField()
    {
        $this->assertEquals($this->field, $this->condition->field());
        $this->assertSame($this->condition, $this->condition->setField('b'));
        $this->assertEquals('b', $this->condition->field());
    }
}
