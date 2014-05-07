<?php

namespace PHPixie\Database\Driver\Mongo\Query\Type;

class Update extends \PHPixie\Database\Driver\Mongo\Query\Items\Implementation implements \PHPixie\Database\Query\Type\Update
{
    public function data($data)
    {
        $this->builder->data($data);
        return $this;
    }
    
    public function getData()
    {
        return $this->builder->getData();
    }
}