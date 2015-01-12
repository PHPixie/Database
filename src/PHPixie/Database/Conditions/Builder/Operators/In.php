<?php

namespace PHPixie\Database\Conditions\Builder\Operators;

interface In
{
    public function addInOperatorCondition($field, $values, $logic = 'and', $negate = false);
}