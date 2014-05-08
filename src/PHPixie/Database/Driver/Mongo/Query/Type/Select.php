<?php

namespace PHPixie\Database\Driver\Mongo\Query\Type;

class Select extends \PHPixie\Database\Driver\Mongo\Query\Items implements \PHPixie\Database\Query\Type\Select
{
    public function fields($fields)
    {
        $this->builder->fields($fields);
        return $this;
    }
    
    public function getFields()
    {
        return $this->builder->getFields();
    }
    
    public function type()
    {
        return 'select';
    }
}