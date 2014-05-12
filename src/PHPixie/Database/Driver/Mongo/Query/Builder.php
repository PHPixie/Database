<?php

namespace PHPixie\Database\Driver\Mongo\Query;

class Builder extends \PHPixie\Database\Query\Implementation\Builder
{
    public function setCollection($collection)
    {
        $this->setValue('collection', $collection);
    }
    
    public function addUnset($args)
    {
        $this->addValuesToArray('set', $args, true);
    }
    
    public function addIncrement($args)
    {
        $this->builder->addKeyValuesToArray('increment', $args, true);
    }
    
    public function setBatchData($documents)
    {
        $this->addValuesToArray('set', $documents);
    }
}