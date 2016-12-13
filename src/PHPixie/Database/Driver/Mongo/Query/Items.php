<?php

namespace PHPixie\Database\Driver\Mongo\Query;

abstract class Items extends Item implements \PHPixie\Database\Type\Document\Query\Items
{
    /**
     * @param $limit
     *
     * @return $this
     */
    public function limit($limit)
    {
        $this->builder->setLimit($limit);

        return $this;
    }

    /**
     * @return $this
     */
    public function clearLimit()
    {
        $this->builder->clearValue('limit');

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->builder->getValue('limit');
    }

    /**
     * @param $offset
     *
     * @return $this
     */
    public function offset($offset)
    {
        $this->builder->setOffset($offset);

        return $this;
    }

    /**
     * @return $this
     */
    public function clearOffset()
    {
        $this->builder->clearValue('offset');

        return $this;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->builder->getValue('offset');
    }

    /**
     * @param $field
     * @param $direction
     *
     * @return $this
     */
    public function orderBy($field, $direction)
    {
        $this->builder->addOrderBy($field, $direction);

        return $this;
    }

    /**
     * @param $field
     *
     * @return $this
     */
    public function orderAscendingBy($field)
    {
        $this->builder->addOrderAscendingBy($field);

        return $this;
    }

    /**
     * @param $field
     *
     * @return $this
     */
    public function orderDescendingBy($field)
    {
        $this->builder->addOrderDescendingBy($field);

        return $this;
    }

    /**
     * @return $this
     */
    public function clearOrderBy()
    {
        $this->builder->clearArray('orderBy');

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderBy()
    {
        return $this->builder->getArray('orderBy');
    }

}
