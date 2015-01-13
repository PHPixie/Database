<?php

namespace PHPixie\Database\Driver\Mongo;

class Conditions extends \PHPixie\Database\Type\Document\Conditions
{
    public function container($defaultOperator = '=')
    {
        return new Conditions\Builder\Container($this, $defaultOperator);
    }
}