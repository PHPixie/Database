<?php

namespace PHPixie\Database\Type\Document\Query\Implementation;

abstract class Builder extends \PHPixie\Database\Query\Implementation\Builder
{
    public function __construct($conditions, $valueBuilder)
    {
        parent::__construct($conditions, $valueBuilder);
    }
    
    public function conditionContainer($name = null)
    {
        return parent::conditionContainer($name);
    }
    
    public function startSubdocumentConditionGroup($field, $logic = 'and', $negate = false, $containerName = null)
    {
        $this->conditionContainer($containerName)->startSubdocumentConditionGroup($field, $logic, $negate);
    }
    
    public function startSubarrayItemConditionGroup($field, $logic = 'and', $negate = false, $containerName = null)
    {
        $this->conditionContainer($containerName)->startSubarrayItemConditionGroup($field, $logic, $negate);
    }
    
    public function addPlaceholder($logic = 'and', $negate = false, $allowEmpty = true, $containerName = null)
    {
        return parent::addPlaceholder($logic, $negate, $allowEmpty, $containerName);
    }
    
    public function addSubdocumentPlaceholder($field, $logic = 'and', $negate = false, $allowEmpty = true, $containerName = null)
    {
        return $this->conditionContainer($containerName)->addSubdocumentPlaceholder($field, $logic, $negate, $allowEmpty);
    }
    
    public function addSubarrayItemPlaceholder($field, $logic = 'and', $negate = false, $allowEmpty = true, $containerName = null)
    {
        return $this->conditionContainer($containerName)->addSubarrayItemPlaceholder($field, $logic, $negate, $allowEmpty);
    }
}
