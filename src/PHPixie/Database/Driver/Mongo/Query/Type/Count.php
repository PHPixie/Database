<?php

namespace PHPixie\Database\Driver\Mongo\Query\Type;

class Count extends \PHPixie\Database\Driver\Mongo\Query\Items implements \PHPixie\Database\Query\Type\Count
{
    public function type()
    {
        return 'count';
    }
    
    public function execute()
    {
        return parent::execute();
    }
}