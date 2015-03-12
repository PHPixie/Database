<?php
namespace PHPixie\Tests\Database\Conditions\Condition;

/**
 * @coversDefaultClass \PHPixie\Database\Conditions\Condition\Implementation
 */
abstract class ImplementationTest extends \PHPixie\Tests\AbstractDatabaseTest
{
    public function setUp()
    {
        $this->condition = $this->condition();
    }

    /**
     * @covers ::negate
     * @covers ::isNegated
     * @covers ::setIsNegated
     */
    public function testNegation()
    {
        $this->assertEquals(false, $this->condition->isNegated());
        $this->assertEquals($this->condition, $this->condition->negate());
        $this->assertEquals(true, $this->condition->isNegated());
        
        $this->assertEquals($this->condition, $this->condition->setIsNegated(true));
        $this->assertEquals(true, $this->condition->isNegated());
        $this->assertEquals($this->condition, $this->condition->setIsNegated(false));
        $this->assertEquals(false, $this->condition->isNegated());
    }

    /**
     * @covers ::logic
     * @covers ::setLogic
     */
    public function testLogic()
    {
        $this->assertEquals($this->condition, $this->condition->setLogic('or'));
        $this->assertEquals('or', $this->condition->logic());
        
        $this->setExpectedException('\PHPixie\Database\Exception\Builder');
        $this->condition->setLogic('test');
    }
    
    abstract protected function condition();
}
