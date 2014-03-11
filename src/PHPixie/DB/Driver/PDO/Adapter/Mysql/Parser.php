<?php

namespace PHPixie\DB\Driver\PDO\Adapter\Mysql;

class Parser extends \PHPixie\DB\SQL\Parser
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
}
