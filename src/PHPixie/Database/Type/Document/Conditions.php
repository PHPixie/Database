<?php

namespace PHPixie\Database\Type\Document;

class Conditions implements \PHPixie\Database\Type\Document\Conditions\Builder\Container\Builder
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

    public function subdocumentGroup($field)
    {
        return new Conditions\Condition\Collection\Embedded\Group\Subdocument($field);
    }
    
    public function subarrayItemGroup($field)
    {
        return new Conditions\Condition\Collection\Embedded\Group\SubarrayItem($field);
    }
    
    public function placeholder($defaultOperator = '=', $allowEmpty = true)
    {
        $container = $this->container($defaultOperator);
        return new Conditions\Condition\Collection\Placeholder($container, $allowEmpty);
    }
    
    public function subdocumentPlaceholder($field, $defaultOperator = '=', $allowEmpty = true)
    {
        $container = $this->container($defaultOperator);
        return new Conditions\Condition\Collection\Embedded\Placeholder\Subdocument($container, $field, $allowEmpty);
    }
    
    public function subarrayItemPlaceholder($field, $defaultOperator = '=', $allowEmpty = true)
    {
        $container = $this->container($defaultOperator);
        return new Conditions\Condition\Collection\Embedded\Placeholder\SubarrayItem($container, $field, $allowEmpty);
    }
}