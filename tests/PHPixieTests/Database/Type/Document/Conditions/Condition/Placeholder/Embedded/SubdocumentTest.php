<?php

namespace PHPixieTests\Database\Type\Document\Conditions\Condition\Placeholder\Embedded;

/**
 * @coversDefaultClass \PHPixie\Database\Type\Document\Conditions\Condition\Placeholder\Embedded\Subdocument
 */
class SubdocumentTest extends \PHPixieTests\Database\Type\Document\Conditions\Condition\Placeholder\EmbeddedTest
{
    protected function placeholder($allowEmpty = true)
    {
        return new \PHPixie\Database\Type\Document\Conditions\Condition\Placeholder\Embedded\Subdocument(
            $this->container,
            $this->field,
            $allowEmpty
        );   
    }
}