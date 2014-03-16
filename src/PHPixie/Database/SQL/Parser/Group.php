<?php

namespace PHPixie\Database\SQL\Parser;

abstract class Group
{
    protected $database;
    protected $operatorParser;

    public function __construct($database, $operatorParser)
    {
        $this->database = $database;
        $this->operatorParser = $operatorParser;
    }

    public function parse($group)
    {
        $expr = $this->database->expr();
        $this->appendGroup($group, $expr);

        return $expr;
    }

    protected function appendCondition($condition, $expr)
    {
        if ($condition->negated()) {
            $expr->sql .= 'NOT ';
        }

        if ($condition instanceof \PHPixie\Database\Conditions\Condition\Operator) {
            $expr->append($this->operatorParser->parse($condition));

        } elseif ($condition instanceof \PHPixie\Database\Conditions\Condition\Group || $condition instanceof \PHPixie\Database\Conditions\Condition\Placeholder) {
            $expr->sql.= "( ";
            $this->appendGroup($condition->conditions(), $expr);
            $expr->sql.= " )";

        } else {
            throw new \PHPixie\Database\Exception\Parser("Unexpected condition type encountered");
        }
    }

    protected function appendGroup($group, $expr)
    {
        foreach ($group as $key=>$condition) {
            if ($key > 0)
                $expr->sql.= ' '.strtoupper($condition->logic).' ';
            $this->appendCondition($condition, $expr);
        }
    }

}
