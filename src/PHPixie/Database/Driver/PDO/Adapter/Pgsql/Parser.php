<?php

namespace PHPixie\Database\Driver\PDO\Adapter\Pgsql;

class Parser extends \PHPixie\Database\SQL\Parser
{
    protected $supportedJoins = array(
        'inner' => 'INNER',
        'cross' => 'CROSS',
        'left'  => 'LEFT',
        'left_outer' => 'LEFT OUTER',
        'right_outer' => 'RIGHT OUTER',
        'natural' => 'NATURAL',
        'natural_left' => 'NATURAL LEFT',
        'natural_right' => 'NATURAL RIGHT',
        'natural_left outer' => 'NATURAL LEFT OUTER',
        'natural_right outer' => 'NATURAL RIGHT OUTER'
    );

    protected function appendEmptyInsertValues($expr)
    {
        $expr->sql.= " DEFAULT VALUES";
    }
}
