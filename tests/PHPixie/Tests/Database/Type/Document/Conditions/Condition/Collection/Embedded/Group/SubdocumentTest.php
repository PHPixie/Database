<?php

namespace PHPixie\Tests\Database\Type\Document\Conditions\Condition\Collection\Embedded\Group;

/**
 * @coversDefaultClass \PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded\Group\Subdocument
 */
class SubdocumentTest extends \PHPixie\Tests\Database\Type\Document\Conditions\Condition\Collection\Embedded\GroupTest
{
    protected function embeddedGroup($field)
    {
        return new \PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded\Group\Subdocument($field);
    }
}