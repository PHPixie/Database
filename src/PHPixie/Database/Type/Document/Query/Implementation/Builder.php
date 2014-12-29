<?php

namespace PHPixie\Database\Type\Document\Query\Implementation;

class Builder extends \PHPixie\Database\Query\Implementation\Builder
{
    public function __construct($containerBuilder, $valueBuilder)
    {
        parent::__construct($containerBuilder, $valueBuilder);
    }
    
    public function conditionContainer($name = null)
    {
        return parent::conditionContainer($name);
    }
    
    public function addPlaceholder($logic = 'and', $negate = false, $allowEmpty = true, $containerName = null)
    {
        return parent::addPlaceholder($logic, $negate, $allowEmpty, $containerName);
    }
    
    public function addSubdocumentCondition($field, $logic = 'and', $negate = false, $allowEmpty = true, $containerName = null)
    {
        return $this->conditionContainer($containerName)->addSubdocumentCondition($field, $logic, $negate, $allowEmpty);
    }
    
    public function addArrayItemCondition($field, $logic = 'and', $negate = false, $allowEmpty = true, $containerName = null)
    {
        return $this->conditionContainer($containerName)->addArrayItemCondition($field, $logic, $negate, $allowEmpty);
    }
}
