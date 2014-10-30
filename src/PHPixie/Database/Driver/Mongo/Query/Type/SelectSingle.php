<?php

namespace PHPixie\Database\Driver\Mongo\Query\Type;

class SelectSingle extends \PHPixie\Database\Driver\Mongo\Query\Item
{
    public function fields($fields)
    {
        $this->builder->addFields(func_get_args());

        return $this;
    }

    public function clearFields()
    {
        $this->builder->clearArray('fields');

        return $this;
    }

    public function getFields()
    {
        return $this->builder->getArray('fields');
    }

    public function type()
    {
        return 'selectSingle';
    }

    public function execute()
    {
        return parent::execute()->current();
    }
}
