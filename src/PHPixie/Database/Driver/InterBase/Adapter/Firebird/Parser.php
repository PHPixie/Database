<?php
namespace PHPixie\Database\Driver\InterBase\Adapter\Firebird;

/**
 * Class Parser
 * @package PHPixie\Database\Driver\InterBase\Adapter\Firebird
 */
class Parser extends \PHPixie\Database\Type\SQL\Parser
{
    /**
     * @var array
     */
    protected $supportedJoins = array(
        'inner'               => 'INNER',
        'cross'               => 'CROSS',
        'left'                => 'LEFT',
        'left_outer'          => 'LEFT OUTER',
        'right_outer'         => 'RIGHT OUTER',
        'natural'             => 'NATURAL',
        'natural_left'        => 'NATURAL LEFT',
        'natural_right'       => 'NATURAL RIGHT',
        'natural_left outer'  => 'NATURAL LEFT OUTER',
        'natural_right outer' => 'NATURAL RIGHT OUTER',
    );

    /**
     * @param \PHPixie\Database\Type\SQL\Expression $expr
     * @param int                                   $limit
     * @param int                                   $offset
     */
    protected function appendLimitOffsetValues($expr, $limit, $offset)
    {
        if($limit !== null) {
            if($offset === null) {
                $offset = 1;
            } else {
                $limit = $offset + $limit - 1;
            }
            $expr->sql .= " ROWS $offset";
            $expr->sql .= " TO $limit";
        }
    }

}

