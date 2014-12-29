<?php

namespace PHPixie\Database\Type\Document;

class Conditions implements \PHPixie\Database\Conditions\Builder\Container\Builder
{
    
    protected $conditions;
    
    public function __construct($conditions)
    {
        $this->conditions = $conditions;
    }
    
    public function container($defaultOperator = '=')
    {
        return new Conditions\Builder\Container($this->conditions, $this, $defaultOperator);
    }

    public function placeholder($defaultOperator = '=', $allowEmpty = true)
    {
        $container = $this->container($defaultOperator);
        return new Conditions\Condition\Placeholder($container, $allowEmpty);
    }
    
    public function subdocument($field, $defaultOperator = '=', $allowEmpty = true)
    {
        $container = $this->container($defaultOperator);
        return new Conditions\Condition\Placeholder\Subdocument($container, $field, $allowEmpty);
    }
    
    public function arraySubdocument($field, $defaultOperator = '=', $allowEmpty = true)
    {
        $container = $this->container($defaultOperator);
        return new Conditions\Condition\Placeholder\Subdocument\ArrayItem($container, $field, $allowEmpty);
    }
}