<?php

namespace PHPixie\Tests\Database\Driver\Mongo;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Conditions
 */
class ConditionsTest extends \PHPixie\Tests\Database\Type\Document\ConditionsTest
{
    protected function conditions()
    {
        return new \PHPixie\Database\Driver\Mongo\Conditions();
    }
}