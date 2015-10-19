<?php

namespace PHPixie\Database\Type\SQL\Parser;

abstract class Fragment
{
    protected $quote;

    public function quote($str)
    {
        return $this->quote.$str.$this->quote;
    }

    public function appendColumn($column, $expr)
    {
        if (is_object($column) && $column instanceof \PHPixie\Database\Type\SQL\Expression)
            return $expr->append($column);

        if (strpos($column, '.')) {
            $column = explode('.', $column);
            $expr->sql.= $this->quote($column[0]).'.';
            $column = $column[1];
        }

        if ($column !== '*')
            $column = $this->quote($column);

        $expr->sql.= $column;

        return $expr;
    }

    public function appendTable($table, $expr, $alias = null)
    {
        if (is_string($table)) {
            $table = explode('.', $table);
            foreach($table as $key => $part) {
                if($key !== 0) {
                    $expr->sql.= '.';    
                }
                $expr->sql.= $this->quote($part);
            }
        
        } elseif ( 
            ($isQuery = $table instanceof \PHPixie\Database\Type\SQL\Query) || 
            $table instanceof \PHPixie\Database\Type\SQL\Expression
        ) {

            if ($isQuery)
                $table = $table->parse();

            $expr->sql.= "( ";
            $expr->append($table);
            $expr->sql.= " )";
        } else {
            $class = get_class($table);
            throw new \PHPixie\Database\Exception\Parser("Parameter type '$class' cannot be used as a table");
        }

        if ($alias !== null)
            $expr->sql.= " AS ".$this->quote($alias);

        return $expr;
    }

    public function appendValue($value, $expr)
    {
        if ($value instanceof \PHPixie\Database\Type\SQL\Expression) {
            $expr->append($value);
        } elseif ($value instanceof \PHPixie\Database\Type\SQL\Query) {
            $expr->sql.= "( ";
            $expr->append($value-> parse());
            $expr->sql.= " )";
        } else {
            $expr->sql.= '?';
            $expr->params[]= $value;
        }

        return $expr;
    }
}
