<?php

namespace PHPixie\Tests\Database\Type\Document\Conditions\Condition\Collection;

/**
 * @coversDefaultClass \PHPixie\Database\Type\Document\Conditions\Condition\Collection\Placeholder
 */
class PlaceholderTest extends \PHPixie\Tests\Database\Conditions\Condition\Collection\PlaceholderTest
{
    /**
     * @covers \PHPixie\Database\Conditions\Condition\Collection\Placeholder::__construct
     * @covers \PHPixie\Database\Type\Document\Conditions\Condition\Collection\Placeholder::__construct
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers \PHPixie\Database\Conditions\Condition\Collection\Placeholder::container
     * @covers \PHPixie\Database\Type\Document\Conditions\Condition\Collection\Placeholder::container
     * @covers ::container
     * @covers ::<protected>
     */
    public function testContainer()
    {
        parent::testContainer();
    }
    
    protected function getContainer()
    {
        return $this->quickMock('\PHPixie\Database\Type\Document\Conditions\Builder\Container', array('getConditions'));
    }
    
    protected function placeholder($allowEmpty = true)
    {
        return new \PHPixie\Database\Type\Document\Conditions\Condition\Collection\Placeholder($this->container, $allowEmpty);
    }
}
