<?php

namespace PHPixie\Database\Driver\Mongo\Parser;

class Operator extends \PHPixie\Database\Parser\Operator
{
    protected $operators = array(
        'generic' => array('<','<=','=','!=','ne', '>=','>','exists','type','mod','where','all','elemMatch','size' ),
        'between' => array('between', 'not between'),
        'in'      => array('in', 'nin', 'not in'),
        'regex'   => array('regex', 'not regex')
    );

    protected $operatorMap = array(
        '<'      => 'lt',
        '<='     => 'lte',
        '!='     => 'ne',
        '>='     => 'gte',
        '>'      => 'gt'
    );

    protected $negationMap = array(
        'lt'  => 'gte',
        'lte' => 'gt',
        '='   => 'ne',
        'gt'  => 'lte',
        'gte' => 'lt',
        'ne'  => '=',
        'in'  => 'nin',
        'nin' => 'in'
    );

    protected function parseRegex($field, $operator, $value, $negated)
    {
        if (count($value)!==1 && !is_string($value[0]))
            throw new \PHPixie\Database\Exception\Parser("The '$operator' operator requires a single string parameter to be passed");

        $value[0] = new \MongoRegex($value[0]);

        if ($operator == 'not regex') {
            $negated = !$negated;
            $operator = 'regex';
        }

        return $this->parseGeneric($field, $operator, $value, $negated);
    }

    protected function parseIn($field, $operator, $value, $negated)
    {
        if (count($value)!==1 && !is_array($value[0]))
            throw new \PHPixie\Database\Exception\Parser("The '$operator' operator requires a single array parameter to be passed");

        if ($operator == 'not in')
            $operator = 'nin';

        return $this->parseGeneric($field, $operator, $value, $negated);
    }

    protected function parseGeneric($field, $operator, $value, $negated)
    {
        if (!is_array($value) || count($value) !== 1)
            throw new \PHPixie\Database\Exception\Parser("The '$operator' operator requires a single array parameter to be passed");

        if(isset($this->operatorMap[$operator]))
            $operator = $this->operatorMap[$operator];
        if ($negated && isset($this->negationMap[$operator])) {
            $operator = $this->negationMap[$operator];
            $negated = false;
        }

        $value = $value[0];

        if ($operator === '=') {
            $condition = $value;
        } else {
            $condition =  array(('$'.$operator) => $value);
            if ($negated)
                $condition = array('$not' => $condition);
        }

        return array($field => $condition);
    }

    protected function parseBetween($field, $operator, $range, $negated)
    {
        if (count($range) !== 2)
            throw new \PHPixie\Database\Exception\Parser("The '$operator' operator requires two parameters to be passed");
        if($operator === 'not between')
            $negated = !$negated;

        $left  = !$negated ? '$gte' : '$lt';
        $right = !$negated ? '$lte' : '$gt';

        return array(
            $field => array(
                    $left  => $range[0],
                    $right => $range[1]
                )
            );
    }

}
