<?php

namespace PHPixie\Database\Type\Document\Conditions;

interface Builder extends \PHPixie\Database\Conditions\Builder
{
    public function addPlaceholder($logic = 'and', $negate = false, $allowEmpty = true);
    public function addSubdocumentCondition($logic, $negate, $field);
    public function addArrayItemCondition($logic, $negate, $field);
}
