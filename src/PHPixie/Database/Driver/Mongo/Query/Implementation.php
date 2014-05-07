<?php

namespace PHPixie\Database\Driver\Mongo\Query;

class Implementation extends \PHPixie\Database\Query\Implementation
{
    public function collection($collection)
    {
        $this->builder->collection($collection);
        return $this;
    }
    
    public function getCollection()
    {
        return $this->builder->getCollection();
    }
    
    public function parse()
    {
        return $this->parser->parse($this);
    }

    public function execute()
    {
        return $this->connection->run($this->parse());
    }

}