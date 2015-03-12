<?php

namespace PHPixie\Tests\Database\Driver\Mongo\Conditions\Builder;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Conditions\Builder\Container
 */
class ContainerTest extends \PHPixie\Tests\Database\Type\Document\Conditions\Builder\ContainerTest
{
    /**
     * @covers ::addInOperatorCondition
     * @covers ::<protected>
     */
    public function testInOperator()
    {
        $this->operatorTest(
            'addInOperatorCondition',
            array('pixie', array(1)),
            array('pixie', 'in', array(array(1)))
        );
    }
    
    protected function conditions()
    {
        return new \PHPixie\Database\Driver\Mongo\Conditions();
    }
    
    protected function container($defaultOperator = '=')
    {
        return new \PHPixie\Database\Driver\Mongo\Conditions\Builder\Container($this->conditions, $defaultOperator);
    }
}