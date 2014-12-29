<?php

namespace PHPixieTests\Database\Type\Document\Conditions\Condition;

/**
 * @coversDefaultClass \PHPixie\Database\Type\Document\Conditions\Condition\Placeholder
 */
class PlaceholderTest extends \PHPixieTests\Database\Conditions\Condition\PlaceholderTest
{
    protected function getContainer()
    {
        return $this->quickMock('\PHPixie\Database\Type\Document\Conditions\Builder\Container', array('getConditions'));
    }
    
    protected function placeholder($allowEmpty = true)
    {
        return new \PHPixie\Database\Type\Document\Conditions\Condition\Placeholder($this->container, $allowEmpty);
    }
}
