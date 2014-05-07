<?php

namespace PHPixie\Driver\PDO\Query\Type;

class Update extends \PHPixie\Driver\PDO\Query\Items\Implementation implements \PHPixie\Driver\SQL\Query\Type\Update
{
    public function data($data)
    {
        $this->builder->data($data);
        return $this;
    }
    
    public function getData($data)
    {
        $this->builder->getData();
    }
}