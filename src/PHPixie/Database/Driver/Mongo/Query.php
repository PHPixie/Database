<?php

namespace PHPixie\Database\Driver\Mongo;

abstract class Query extends \PHPixie\Database\Query\Implementation
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
        return parent::parse();
    }

    public function execute()
    {
        return $this->connection->run($this->parse());
    }

}