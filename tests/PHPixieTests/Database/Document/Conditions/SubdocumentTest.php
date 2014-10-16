<?php

namespace PHPixieTests\Database\Document\Conditions;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Conditions\Subdocument
 */
class SubdocumentTest extends \PHPixieTests\Database\Conditions\BuilderTest
{
    public function setUp()
    {
        $database = new \PHPixie\Database(null);
        $operatorParser = new \PHPixie\Database\Driver\Mongo\Parser\Operator();
        $groupParser = new \PHPixie\Database\Driver\Mongo\Parser\Group($database->driver('Mongo'), $operatorParser);
        $this->conditions = new \PHPixie\Database\Conditions;
        $this->builder = new \PHPixie\Database\Driver\Mongo\Conditions\Subdocument($this->conditions, $groupParser);
    }
}
