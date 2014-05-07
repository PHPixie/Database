<?php

namespace PHPixie\Database\Driver\Mongo\Query\Type;

class Insert extends \PHPixie\Database\Driver\Mongo\Query\Items\Implementation implements \PHPixie\Database\Query\Type\Insert
{
    public function data($data)
    {
        $this->builder->data($data);
        return $this;
    }
    
    public function batchData($documents)
    {
        $this->builder->batchData($documents);
        return $this;
    }
    
    public function getData()
    {
        return $this->builder->getData();
    }
    
    public function type()
    {
        return 'insert';
    }
}