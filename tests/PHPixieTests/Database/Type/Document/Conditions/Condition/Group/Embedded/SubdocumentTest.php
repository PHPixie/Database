<?php

namespace PHPixieTests\Database\Type\Document\Conditions\Condition\Group;

/**
 * @coversDefaultClass \PHPixie\Database\Type\Document\Conditions\Condition\Group\Embedded\Subdocument
 */
class SubdocumentTest extends \PHPixieTests\Database\Type\Document\Conditions\Condition\Group\EmbeddedTest
{
    protected function embeddedGroup($field)
    {
        return new \PHPixie\Database\Type\Document\Conditions\Condition\Group\Embedded\Subdocument($field);
    }
}