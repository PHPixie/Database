<?php

namespace PHPixie\Driver\PDO\Query\Type;

class Count extends \PHPixie\Driver\PDO\Query\Items implements \PHPixie\Driver\SQL\Query\Type\Count
{
    public function execute()
    {
        return parent::execute()->get('count');
    }
}