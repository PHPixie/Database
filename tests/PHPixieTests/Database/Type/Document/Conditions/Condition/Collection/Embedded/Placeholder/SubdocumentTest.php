<?php

namespace PHPixieTests\Database\Type\Document\Conditions\Condition\Collection\Embedded\Placeholder;

/**
 * @coversDefaultClass \PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded\Placeholder\Subdocument
 */
class SubdocumentTest extends \PHPixieTests\Database\Type\Document\Conditions\Condition\Collection\Embedded\PlaceholderTest
{
    protected function placeholder($allowEmpty = true)
    {
        return new \PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded\Placeholder\Subdocument(
            $this->container,
            $this->field,
            $allowEmpty
        );   
    }
}