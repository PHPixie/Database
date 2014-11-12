<?php

namespace PHPixie\Database\Conditions\Condition;

class Operator extends \PHPixie\Database\Conditions\Condition
{
    public $field;
    public $operator;
    public $values;

    public function __construct($field, $operator, $values)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->values = $values;
    }
}
