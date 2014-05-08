<?php

namespace PHPixie\Database\Driver\Mongo\Query;

class Builder extends \PHPixie\Database\Query\Implementation\Builder
{
    protected $collection;
    
    public function collection($collection)
    {
        $this->collection = $collection;
    }

    public function getCollection()
    {
        return $this->collection;
    }
    
    public function batchData($documents)
    {
        $this->data = $this->driver->batchData($documents);
    }
}