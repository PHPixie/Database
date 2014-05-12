<?php

namespace PHPixie\Database\Driver\Mongo\Query;

abstract class Items extends Item implements \PHPixie\Database\Query\Items
{
    public function limit($limit)
    {
        $this->builder->setLimit($limit);
        return $this;
    }

    public function clearLimit()
    {
        $this->builder->clearValue('limit');
        return $this;
    }
    
    public function getLimit()
    {
        return $this->builder->getValue('limit');
    }

    public function offset($offset)
    {
        $this->builder->setOffset($offset);
        return $this;
    }

    public function clearOffset()
    {
        $this->builder->clearValue('offset');
        return $this;
    }
    
    public function getOffset()
    {
        return $this->builder->getValue('offset');
    }

    public function orderAscendingBy($field)
    {
		$this->builder->addOrderAscendingBy($field);
        return $this;
    }

    public function orderDescendingBy($field)
    {
		$this->builder->addOrderDescendingBy($field);
        return $this;
    }

    public function clearOrderBy()
    {
        $this->builder->clearArray('orderBy');
        return $this;
    }
    
    public function getOrderBy()
    {
        return $this->builder->getArray('orderBy');
    }

}