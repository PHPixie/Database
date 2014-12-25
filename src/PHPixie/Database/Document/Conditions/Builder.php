<?php

namespace PHPixie\Database\Document\Conditions;

interface Builder extends \PHPixie\Database\Conditions\Builder
{
    public function addSubdocumentCondition($logic, $negate, $field);
    public function addArrayItemCondition($logic, $negate, $field);
}
