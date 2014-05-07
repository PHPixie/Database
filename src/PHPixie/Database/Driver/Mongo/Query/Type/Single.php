<?php

namespace PHPixie\Database\Driver\Mongo\Query\Type;

class Single extends \PHPixie\Database\Driver\Mongo\Query\Items\Item
{
    public function type()
    {
        return 'single';
    }
}