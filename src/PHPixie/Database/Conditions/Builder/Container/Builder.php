<?php

namespace PHPixie\Database\Conditions\Builder\Container;

interface Builder
{
    public function container($defaultOperator = '=');
}