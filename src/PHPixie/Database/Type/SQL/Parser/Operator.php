<?php

namespace PHPixie\Database\Type\SQL\Parser;

abstract class Operator extends \PHPixie\Database\Parser\Operator
{
    protected $database;
    protected $fragmentParser;

    protected $operators = array(
        'compare' => array('<', '<=', '=', '<>', '!=', '>=', '>', '<*', '<=*', '=*', '<>*', '!=*', '>=*', '>*'),
        'pattern' => array('like', 'not like', 'regexp', 'not regexp'),
        'in'      => array('in', 'not in'),
        'between' => array('between', 'not between'),
    );

    public function __construct($database, $fragmentParser)
    {
        $this->database = $database;
        $this->fragmentParser = $fragmentParser;
        parent::__construct();
    }

    protected function prefix($field, $operator)
    {
        $expr = $this->database->sqlExpression();
        $this->fragmentParser->appendColumn($field, $expr);
        $expr->sql .= ' '.strtoupper($operator).' ';

        return $expr;
    }

    protected function singleValue($values, $operator)
    {
        if(count($values) !== 1)
            throw new \PHPixie\Database\Exception\Parser(strtoupper($operator)." operator requires a single parameter");

        return $values[0];
    }

    protected function parseCompare($field, $operator, $values)
    {
        $isColumn =  false;

        if (substr($operator, -1, 1) === '*') {
            $operator = substr($operator, 0, -1);
            $isColumn = true;
        }

        $value = $this->singleValue($values, $operator);

        if ($operator === '!=')
            $operator = '<>';

        if ($value === null) {
            if ($isColumn)
                throw new \PHPixie\Database\Exception\Parser("A column comparison operator '{$operator}*' was given a NULL instead of column");

            if ($operator === '=') {
                $operator = 'is';
            } elseif ($operator === '<>') {
                $operator = 'is not';
            }
        }

        $expr = $this->prefix($field, $operator);

        if ($value === null) {
            $expr->sql.= "NULL";
        } elseif ($isColumn) {
            $this->fragmentParser->appendColumn($value, $expr);
        } else {
            $this->fragmentParser->appendValue($value, $expr);
        }

        return $expr;
    }

    protected function parseBetween($field, $operator, $range)
    {
        if (count($range) !== 2)
            throw new \PHPixie\Database\Exception\Parser(strtoupper($operator)." operator parameter requires two parameters");

        $expr = $this->prefix($field, $operator);
        $this->fragmentParser->appendValue($range[0], $expr);
        $expr->sql.= " AND ";
        $this->fragmentParser->appendValue($range[1], $expr);

        return $expr;
    }

    protected function parsePattern($field, $operator, $values)
    {
        $value = $this->singleValue($values, $operator);

        $expr = $this->prefix($field, $operator);
        $this->fragmentParser->appendValue($value, $expr);

        return $expr;
    }

    protected function parseIn($field, $operator, $values)
    {
        $value = $this->singleValue($values, $operator);
        
        if(is_array($value) && count($value) === 0) {
            $condition = $operator === 'in' ? 'FALSE' : 'TRUE';
            return $this->database->sqlExpression($condition);
        }
        
        $expr = $this->prefix($field, $operator);
        if (is_array($value)) {
            $listSql = str_pad('', count($value) * 3 - 2, '?, ');
            $expr->sql.= "($listSql)";
            $expr->params = array_merge($expr->params, $value);
        } elseif ($value instanceof \PHPixie\Database\Type\SQL\Query) {
            $subquery = $value->parse();
            $expr->sql.= "( ";
            $expr->append($subquery);
            $expr->sql.= " )";
        } elseif ($value instanceof \PHPixie\Database\Type\SQL\Expression) {
            $expr->sql.= "( ";
            $expr->append($value);
            $expr->sql.= " )";
        } else {
            throw new \PHPixie\Database\Exception\Parser(strtoupper($operator)." operator parameter must be either an array, a query or an expression");
        }

        return $expr;
    }
}
