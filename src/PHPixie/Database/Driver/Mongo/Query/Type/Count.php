<?php

namespace PHPixie\Database\Driver\Mongo\Query\Type;

class Count extends \PHPixie\Database\Driver\Mongo\Query\Items\Implementation implements \PHPixie\Database\Query\Type\Count
{
    public function count()
    {
        return $this->execute();
    }
    
    public function type()
    {
        return 'count';
    }
}