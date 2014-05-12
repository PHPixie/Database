<?php

namespace PHPixie\Database\Driver\Mongo;

abstract class Query extends \PHPixie\Database\Query\Implementation
{
    protected $parser;
    
    public function collection($collection)
    {
        $this->builder->setCollection($collection);
        return $this;
    }

    public function clearCollection()
    {
        $this->builder->clearValue('collection');
        return $this;
    }
    
    public function getCollection()
    {
        return $this->builder->getValue('collection');
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