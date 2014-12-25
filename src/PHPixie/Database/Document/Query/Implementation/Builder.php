<?php

namespace PHPixie\Database\Document\Query\Implementation;

class Builder extends \PHPixie\Database\Query\Implementation\Builder
{
    public function addSubdocumentCondition($logic, $negate, $field, $containerName = null)
    {
        return $this->conditionContainer($containerName)->addSubdocumentCondition($logic, $negate, $field);
    }
    
    public function addArrayItemCondition($logic, $negate, $field, $containerName = null)
    {
        return $this->conditionContainer($containerName)->addArrayItemCondition($logic, $negate, $field);
    }
}
