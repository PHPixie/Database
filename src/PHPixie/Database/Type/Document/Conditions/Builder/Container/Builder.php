<?php

namespace PHPixie\Database\Type\Document\Conditions\Builder\Container;

interface Builder extends \PHPixie\Database\Conditions\Builder\Container\Builder
{
    public function container($defaultOperator = '=');
}