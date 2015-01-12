<?php

namespace PHPixie\Database\Driver\Mongo\Conditions\Builder;

class Container extends    \PHPixie\Database\Type\Document\Conditions\Builder\Container
                implements \PHPixie\Database\Driver\Mongo\Conditions\Builder
{
    public function addInOperatorCondition($field, $values, $logic = 'and', $negate = false)
    {
        return $this-addOperatorCondition($logic, $negate, $field, 'in', array($values));
    }
}