<?php

namespace PHPixieTests\Database\Type\Document\Conditions\Condition\Placeholder\Subdocument;

/**
 * @coversDefaultClass \PHPixie\Database\Type\Document\Conditions\Condition\Placeholder\Subdocument\ArrayItem
 */
class ArrayItemTest extends \PHPixieTests\Database\Type\Document\Conditions\Condition\Placeholder\SubdocumentTest
{
    protected function placeholder($allowEmpty = true)
    {
        return new \PHPixie\Database\Type\Document\Conditions\Condition\Placeholder\Subdocument\ArrayItem(
            $this->container,
            $this->field,
            $allowEmpty
        );   
    }
}