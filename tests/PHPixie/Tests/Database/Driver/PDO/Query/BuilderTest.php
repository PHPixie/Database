<?php

namespace PHPixie\Tests\Database\Driver\PDO\Query;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Query\Builder
 */
class BuilderTest extends \PHPixie\Tests\Database\Type\SQL\Query\Implementation\BuilderTest
{
    protected function container()
    {
        return $this->quickMock('\PHPixie\Database\Driver\PDO\Conditions\Builder\Container', array());
    }
    
    protected function builder()
    {
        return new \PHPixie\Database\Driver\PDO\Query\Builder($this->conditionsMock, $this->valuesMock);
    }
}