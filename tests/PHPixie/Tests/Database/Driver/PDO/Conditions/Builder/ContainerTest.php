<?php

namespace PHPixie\Tests\Database\Driver\PDO\Conditions\Builder;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Conditions\Builder\Container
 */
class ContainerTest extends \PHPixie\Tests\Database\Type\SQL\Conditions\Builder\ContainerTest
{
    protected function conditions()
    {
        return new \PHPixie\Database\Driver\PDO\Conditions();
    }
    
    protected function container($defaultOperator = '=')
    {
        return new \PHPixie\Database\Driver\PDO\Conditions\Builder\Container($this->conditions, $defaultOperator);
    }
}