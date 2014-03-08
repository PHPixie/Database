<?php
namespace PHPixieTests\DB\Conditions;

/**
 * @coversDefaultClass \PHPixie\DB\Conditions\Condition
 */
abstract class ConditionTest extends PHPUnit_Framework_TestCase
{
    protected $condition;

    /**
     * @covers ::negate
     * @covers ::negated
     */
    public function testNegation()
    {
        $this->assertEquals(false, $this->condition->negated());
        $this->assertEquals($this->condition, $this->condition->negate());
        $this->assertEquals(true, $this->condition->negated());
    }
}
