<?php

namespace PHPixie\Database\Driver\PDO;

class Conditions extends \PHPixie\Database\Type\SQL\Conditions
{
    public function container($defaultOperator = '=')
    {
        return new Conditions\Builder\Container($this, $defaultOperator);
    }
}