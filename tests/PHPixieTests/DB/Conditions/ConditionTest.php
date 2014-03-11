<?php
namespace PHPixieTests\DB\Conditions;

/**
 * @coversDefaultClass \PHPixie\DB\Conditions\Condition
 */
abstract class ConditionTest extends \PHPixieTests\AbstractDBTest
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
    
    /**
     * @covers ::logic
     * @covers ::setLogic
     */
    public function testLogic()
    {
        $this->assertEquals($this->condition, $this->condition->setLogic('or'));
        $this->assertEquals('or', $this->condition->logic());
    }
}
