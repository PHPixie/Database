<?php

namespace PHPixie\Database\Driver\PDO\Query\Type;

class Delete extends \PHPixie\Database\Driver\PDO\Query\Items implements \PHPixie\Database\Type\SQL\Query\Type\Delete
{
    public function type()
    {
        return 'delete';
    }
}
