<?php

namespace PHPixieTests\Database\Type\Document\Conditions\Condition\Group;

/**
 * @coversDefaultClass \PHPixie\Database\Type\Document\Conditions\Condition\Group\Embedded
 */
abstract class EmbeddedTest extends \PHPixieTests\Database\Conditions\Condition\GroupTest
{
    protected $field = 'pixie';
    
    protected function setUp()
    {
        $this->condition = $this->embeddedGroup($this->field);
    }
    
    /**
     * @covers ::__construct
     * @covers \PHPixie\Database\Type\Document\Conditions\Condition\Group\Embedded::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
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
    
    abstract protected function embeddedGroup($field);
}
