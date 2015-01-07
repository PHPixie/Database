<?php

namespace PHPixie\Database\Conditions\Condition;

interface Field extends \PHPixie\Database\Conditions\Condition
{
    public function field();
    public function setField($field);
}