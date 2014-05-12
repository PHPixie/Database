<?php

namespace PHPixie\Database\Driver\PDO\Query\Type;

class Update extends \PHPixie\Database\Driver\PDO\Query\Items implements \PHPixie\Database\SQL\Query\Type\Update
{
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