<?php

namespace PHPixie\Database\Driver\Mongo\Query\Type;

class Delete extends \PHPixie\Database\Driver\Mongo\Query\Items implements \PHPixie\Database\Query\Type\Delete
{
    public function type()
    {
        return 'delete';
    }
}
