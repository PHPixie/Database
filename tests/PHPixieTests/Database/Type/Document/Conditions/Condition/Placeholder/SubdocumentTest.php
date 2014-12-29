<?php

namespace PHPixieTests\Database\Type\Document\Conditions\Condition\Placeholder;

/**
 * @coversDefaultClass \PHPixie\Database\Type\Document\Conditions\Condition\Placeholder\Subdocument
 */
class SubdocumentTest extends \PHPixieTests\Database\Type\Document\Conditions\Condition\PlaceholderTest
{
    protected $field = 'pixie';
    
    /**
     * @covers ::field
     * @covers ::<protected>
     */
    public function testField()
    {
        $this->assertSame($this->field, $this->condition->field());
    }
    
    protected function placeholder($allowEmpty = true)
    {
        return new \PHPixie\Database\Type\Document\Conditions\Condition\Placeholder\Subdocument(
            $this->container,
            $this->field,
            $allowEmpty
        );   
    }
}