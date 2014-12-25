<?php

namespace PHPixie\Database\Document\Conditions\Builder;

class Container extends    \PHPixie\Database\Conditions\Builder\Container
                implements \PHPixie\Database\Conditions\Builder
{
    public function addSubdocumentCondition($logic, $negate, $field);
    public function addArrayItemCondition($logic, $negate, $field);
}