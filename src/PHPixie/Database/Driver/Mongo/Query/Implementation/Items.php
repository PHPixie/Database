<?php

namespace PHPixie\Database\Driver\Mongo\Query\Items;

abstract class Implementation extends Item implements \PHPixie\Database\Query\Items
{
    public function limit($limit)
    {
        $this->common->limit($limit);
        return $this;
    }

    public function getLimit()
    {
        return $this->common->getLimit();
    }

    public function offset($offset)
    {
        $this->common->offset($offset);
        return $this;
    }

    public function getOffset()
    {
        return $this->common->getOffset();
    }

    public function orderAscendingBy($field)
    {
		$this->common->orderAscendingBy($field);
        return $this;
    }

    public function orderDescendingBy($field)
    {
		$this->common->orderDescendingBy($field);
        return $this;
    }
    
    public function getOrderBy()
    {
        return $this->common->getOrderBy();
    }
}