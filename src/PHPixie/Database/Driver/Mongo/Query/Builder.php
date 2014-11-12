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
        $this->addValuesToArray('unset', $args, true);
    }

    public function addIncrement($args)
    {
        $this->addKeyValuesToArray('increment', $args, true, true, true);
    }

    public function setBatchData($documents)
    {
        $this->setValue('batchData', $documents);
    }
}
