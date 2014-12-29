<?php

namespace PHPixieTests\Database\Type\Document\Conditions\Condition\Placeholder;

/**
 * @coversDefaultClass \PHPixie\Database\Type\Document\Conditions\Condition\Placeholder\Subdocument
 */
abstract class EmbeddedTest extends \PHPixieTests\Database\Type\Document\Conditions\Condition\PlaceholderTest
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