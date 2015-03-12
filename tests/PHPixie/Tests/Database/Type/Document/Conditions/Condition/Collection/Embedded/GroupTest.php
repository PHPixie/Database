<?php

namespace PHPixie\Tests\Database\Type\Document\Conditions\Condition\Collection\Embedded;

/**
 * @coversDefaultClass \PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded\Group
 */
abstract class GroupTest extends \PHPixie\Tests\Database\Conditions\Condition\Collection\GroupTest
{
    protected $field = 'pixie';
    
    /**
     * @covers ::__construct
     * @covers \PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded\Group::__construct
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
    
    protected function condition()
    {
        return $this->embeddedGroup($this->field);
    }
    
    abstract protected function embeddedGroup($field);
}
