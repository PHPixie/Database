<?php

namespace PHPixieTests\Database\Type\Document\Conditions\Condition\Collection;

/**
 * @coversDefaultClass \PHPixie\Database\Type\Document\Conditions\Condition\Collection\Placeholder
 */
class PlaceholderTest extends \PHPixieTests\Database\Conditions\Condition\Collection\PlaceholderTest
{
    protected function getContainer()
    {
        return $this->quickMock('\PHPixie\Database\Type\Document\Conditions\Builder\Container', array('getConditions'));
    }
    
    protected function placeholder($allowEmpty = true)
    {
        return new \PHPixie\Database\Type\Document\Conditions\Condition\Collection\Placeholder($this->container, $allowEmpty);
    }
}
