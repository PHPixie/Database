<?php

namespace PHPixieTests\Database\Type\SQL\Conditions\Builder;

/**
 * @coversDefaultClass \PHPixie\Database\Type\SQL\Conditions\Builder\Container
 */
abstract class ContainerTest extends \PHPixieTests\Database\Conditions\Builder\Container\ConditionsTest
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
    
}