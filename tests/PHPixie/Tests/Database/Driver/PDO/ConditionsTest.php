<?php

namespace PHPixie\Tests\Database\Driver\PDO;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Conditions
 */
class ConditionsTest extends \PHPixie\Tests\Database\Type\SQL\ConditionsTest
{
    protected function conditions()
    {
        return new \PHPixie\Database\Driver\PDO\Conditions();
    }
}