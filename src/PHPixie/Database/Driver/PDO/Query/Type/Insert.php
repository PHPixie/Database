<?php

namespace PHPixie\Driver\PDO\Query\Type;

class Insert extends \PHPixie\Driver\PDO\Query\Items\Implementation implements \PHPixie\Driver\SQL\Query\Type\Insert
{
    public function data($data)
    {
        $this->builder->data($data);
        return $this;
    }
    
    public function batchData($columns, $rows)
    {
        $this->builder->batchData($columns, $rows);
        return $this;
    }
    
    public function getData($data)
    {
        $this->builder->getData();
    }
}