<?php

namespace PHPixie\Database;

class Conditions
{
    public function placeholder($defaultOperator = '=', $allowEmpty = true)
    {
        return new Conditions\Condition\Placeholder($this, $defaultOperator, $allowEmpty);
    }

    public function operator($field, $operator, $values)
    {
        return new Conditions\Condition\Operator($field, $operator, $values);
    }

    public function group()
    {
        return new Conditions\Condition\Group();
    }

    public function builder($defaultOperator = '=')
    {
        return new Conditions\Builder($this, $defaultOperator);
    }
}
