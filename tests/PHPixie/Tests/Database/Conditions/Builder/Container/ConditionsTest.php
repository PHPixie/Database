<?php

namespace PHPixie\Tests\Database\Conditions\Builder\Container;

/**
 * @coversDefaultClass \PHPixie\Database\Conditions\Builder\Container\Conditions
 */
abstract class ConditionsTest extends \PHPixie\Tests\Database\Conditions\Builder\ContainerTest
{
    protected $container;
    

    public function setUp()
    {
        $this->conditions = $this->conditions();
        parent::setUp();
    }
    
    abstract protected function conditions();
}    