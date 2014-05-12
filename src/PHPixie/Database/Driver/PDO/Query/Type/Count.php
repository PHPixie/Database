<?php

namespace PHPixie\Database\Driver\PDO\Query\Type;

class Count extends \PHPixie\Database\Driver\PDO\Query\Items implements \PHPixie\Database\SQL\Query\Type\Count
{
    public function type()
    {
        return 'count';
    }
    
    public function execute()
    {
        return parent::execute()->get('count');
    }
}