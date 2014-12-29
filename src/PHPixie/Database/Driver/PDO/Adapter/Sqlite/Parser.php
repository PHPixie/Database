<?php

namespace PHPixie\Database\Driver\PDO\Adapter\Sqlite;

class Parser extends \PHPixie\Database\Type\SQL\Parser
{
    protected $supportedJoins = array(
        'inner' => 'INNER',
        'cross' => 'CROSS',
        'left'  => 'LEFT',
        'left_outer' => 'LEFT OUTER',
        'natural' => 'NATURAL',
        'natural_inner' => 'NATURAL INNER',
        'natural_left' => 'NATURAL LEFT',
        'natural_left_outer' => 'NATURAL LEFT OUTER'
    );

    protected function deleteQuery($query, $expr)
    {
        $joins = $query->getJoins();
        if (!empty($joins))
            throw new \PHPixie\Database\Exception\Parser("Sqlite doesn't support joins inside DELETE queries");

        return parent::deleteQuery($query, $expr);
    }
}
