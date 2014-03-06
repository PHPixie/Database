<?php

namespace PHPixie\DB\SQL\Parser;

abstract class Group
{
    protected $db;
    protected $operatorParser;

    public function __construct($db, $operatorParser)
    {
        $this->db = $db;
        $this->operatorParser = $operatorParser;
    }

    public function parse($group)
    {
        $expr = $this->db->expr();
        $this->appendGroup($group, $expr);

        return $expr;
    }

    protected function appendCondition($condition, $expr)
    {
        if ($condition->negated()) {
            $expr->sql .= 'NOT ';
        }

        if ($condition instanceof \PHPixie\DB\Conditions\Condition\Operator) {
            $expr->append($this->operatorParser->parse($condition));

        } elseif ($condition instanceof \PHPixie\DB\Conditions\Condition\Group) {
            $expr->sql.= "( ";
            $this->appendGroup($condition->conditions(), $expr);
            $expr->sql.= " )";

        } else {
            throw new \PHPixie\DB\Exception\Parser("Unexpected condition type encountered");
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
