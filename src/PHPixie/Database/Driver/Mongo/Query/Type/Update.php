<?php

namespace PHPixie\Database\Driver\Mongo\Query\Type;

class Update extends \PHPixie\Database\Driver\Mongo\Query\Items
            implements \PHPixie\Database\Query\Type\Update\Incrementable,
                       \PHPixie\Database\Query\Type\Update\Unsetable
{

    public function __construct($connection, $parser, $builder)
    {
        parent::__construct($connection, $parser, $builder);

        $this->aliases = array_merge($this->aliases, array(
            'unset' => '_unset',
        ));
    }

    public function type()
    {
        return 'update';
    }

    public function set($keys)
    {
        $this->builder->addSet(func_get_args());

        return $this;
    }

    public function clearSet()
    {
        $this->builder->clearArray('set');

        return $this;
    }

    public function getSet()
    {
        return $this->builder->getArray('set');
    }

    public function _unset($keys)
    {
        $this->builder->addUnset(func_get_args());

        return $this;
    }

    public function clearUnset()
    {
        $this->builder->clearArray('unset');

        return $this;
    }

    public function getUnset()
    {
        return $this->builder->getArray('unset');
    }

    public function increment($increments)
    {
       $this->builder->addIncrement(func_get_args());

        return $this;
    }

    public function clearIncrement()
    {
        $this->builder->clearArray('increment');

        return $this;
    }

    public function getIncrement()
    {
        return $this->builder->getArray('increment');
    }
}
