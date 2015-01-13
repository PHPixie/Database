<?php

namespace PHPixieTests\Database\Driver\PDO;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Conditions
 */
class ConditionsTest extends \PHPixieTests\Database\Type\SQL\ConditionsTest
{
    protected function conditions()
    {
        return new \PHPixie\Database\Driver\PDO\Conditions();
    }
}