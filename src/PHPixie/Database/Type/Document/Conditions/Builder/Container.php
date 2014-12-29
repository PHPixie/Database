<?php

namespace PHPixie\Database\Type\Document\Conditions\Builder;

class Container extends    \PHPixie\Database\Conditions\Builder\Container
                implements \PHPixie\Database\Conditions\Builder
{
    protected $documentConditions;
    
    public function __construct($conditions, $documentConditions, $defaultOperator = '=')
    {
        $this->documentConditions = $documentConditions;
        parent::__construct($conditions, $defaultOperator);
    }
    
    public function addPlaceholder($logic = 'and', $negate = false, $allowEmpty = true)
    {
        $placeholder = $this->conditions->placeholder($this->defaultOperator, $field, $allowEmpty);
        $this->addToCurrentGroup($logic, $negate, $subdocument);

        return $placeholder->container();
    }
    
    public function addSubdocumentCondition($field, $logic = 'and', $negate = false, $allowEmpty = true)
    {
        $subdocument = $this->conditions->subdocument($this->defaultOperator, $field, $allowEmpty);
        $this->addToCurrentGroup($logic, $negate, $subdocument);

        return $subdocument->container();
    }
    
    public function addArrayItemCondition($field, $logic = 'and', $negate = false, $allowEmpty = true)
    {
        $subdocument = $this->conditions->subdocument($this->defaultOperator, $field, $allowEmpty);
        $this->addToCurrentGroup($logic, $negate, $subdocument);

        return $subdocument->container();
    }
}