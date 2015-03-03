<?php

namespace PHPixie\Database\Type\Document\Conditions\Builder;

abstract class Container extends    \PHPixie\Database\Conditions\Builder\Container\Conditions
                         implements \PHPixie\Database\Type\Document\Conditions\Builder
{
    public function __construct($conditions, $defaultOperator = '=')
    {
        parent::__construct($conditions, $defaultOperator);
    }
    
    public function addPlaceholder($logic = 'and', $negate = false, $allowEmpty = true)
    {
        return parent::addPlaceholder($logic, $negate, $allowEmpty);
    }
    
    public function addSubdocumentPlaceholder($field, $logic = 'and', $negate = false, $allowEmpty = true)
    {
        $subdocument = $this->conditions->subdocumentPlaceholder($field, $this->defaultOperator, $allowEmpty);
        $this->addCondition($logic, $negate, $subdocument);

        return $subdocument->container();
    }
    
    public function addSubarrayItemPlaceholder($field, $logic = 'and', $negate = false, $allowEmpty = true)
    {
        $subdocument = $this->conditions->subarrayItemPlaceholder($field, $this->defaultOperator, $allowEmpty);
        $this->addCondition($logic, $negate, $subdocument);

        return $subdocument->container();
    }
    
    public function startSubdocumentConditionGroup($field, $logic = 'and', $negate = false)
    {
        $group = $this->conditions->subdocumentGroup($field);
        $this->pushGroup($logic, $negate, $group);

        return $this;
    }
    
    public function startSubarrayItemConditionGroup($field, $logic = 'and', $negate = false)
    {
        $group = $this->conditions->subarrayItemGroup($field);
        $this->pushGroup($logic, $negate, $group);

        return $this;
    }
    
    public function startSubdocumentGroup($field)
    {
        return $this->startSubdocumentConditionGroup($field, 'and', false);
    }

    public function startAndSubdocumentGroup($field)
    {
        return $this->startSubdocumentConditionGroup($field, 'and', false);
    }

    public function startOrSubdocumentGroup($field)
    {
        return $this->startSubdocumentConditionGroup($field, 'or', false);
    }

    public function startXorSubdocumentGroup($field)
    {
        return $this->startSubdocumentConditionGroup($field, 'xor', false);
    }

    public function startNotSubdocumentGroup($field)
    {
        return $this->startSubdocumentConditionGroup($field, 'and', true);
    }

    public function startAndNotSubdocumentGroup($field)
    {
        return $this->startSubdocumentConditionGroup($field, 'and', true);
    }

    public function startOrNotSubdocumentGroup($field)
    {
        return $this->startSubdocumentConditionGroup($field, 'or', true);
    }

    public function startXorNotSubdocumentGroup($field)
    {
        return $this->startSubdocumentConditionGroup($field, 'xor', true);
    }
    

    public function startSubarrayItemGroup($field)
    {
        return $this->startSubarrayItemConditionGroup($field, 'and', false);
    }

    public function startAndSubarrayItemGroup($field)
    {
        return $this->startSubarrayItemConditionGroup($field, 'and', false);
    }

    public function startOrSubarrayItemGroup($field)
    {
        return $this->startSubarrayItemConditionGroup($field, 'or', false);
    }

    public function startXorSubarrayItemGroup($field)
    {
        return $this->startSubarrayItemConditionGroup($field, 'xor', false);
    }

    public function startNotSubarrayItemGroup($field)
    {
        return $this->startSubarrayItemConditionGroup($field, 'and', true);
    }

    public function startAndNotSubarrayItemGroup($field)
    {
        return $this->startSubarrayItemConditionGroup($field, 'and', true);
    }

    public function startOrNotSubarrayItemGroup($field)
    {
        return $this->startSubarrayItemConditionGroup($field, 'or', true);
    }

    public function startXorNotSubarrayItemGroup($field)
    {
        return $this->startSubarrayItemConditionGroup($field, 'xor', true);
    }
    
    
}