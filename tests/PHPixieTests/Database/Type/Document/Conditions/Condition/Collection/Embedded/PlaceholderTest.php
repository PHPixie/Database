<?php

namespace PHPixieTests\Database\Type\Document\Conditions\Condition\Collection\Embedded;

/**
 * @coversDefaultClass \PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded\Placeholder
 */
abstract class PlaceholderTest extends \PHPixieTests\Database\Type\Document\Conditions\Condition\Collection\PlaceholderTest
{
    protected $field = 'pixie';
    
    /**
     * @covers ::field
     * @covers ::setField
     * @covers ::<protected>
     */
    public function testField()
    {
        $this->assertSame($this->field, $this->condition->field());
        $this->condition->setField('a');
        $this->assertSame('a', $this->condition->field());
    }
}