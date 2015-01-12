<?php

namespace PHPixieTests\Database\Driver\Mongo;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Conditions
 */
class ConditionsTest extends \PHPixieTests\Database\Type\Document\ConditionsTest
{
    protected function conditions()
    {
        return new \PHPixie\Database\Driver\Mongo\Conditions();
    }
}