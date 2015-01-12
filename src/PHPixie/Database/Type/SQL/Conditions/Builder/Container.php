<?php

namespace PHPixie\Database\Type\SQL\Conditions\Builder;

abstract class Container extends    \PHPixie\Database\Conditions\Builder\Container
                implements \PHPixie\Database\Type\SQL\Conditions\Builder
{
    public function addInOperatorCondition($field, $values, $logic = 'and', $negate = false)
    {
        return $this-addOperatorCondition($logic, $negate, $field, 'in', array($values));
    }
}