<?php

namespace PHPixie\Database\Driver\Mongo\Parser;

class Group extends \PHPixie\Database\Conditions\Logic\Parser
{
    protected $driver;
    protected $operatorParser;

    public function __construct($driver, $operatorParser)
    {
        $this->driver = $driver;
        $this->operatorParser = $operatorParser;
    }

    protected function normalize($condition)
    {
        if ($condition instanceof \PHPixie\Database\Conditions\Condition\Group || $condition instanceof \PHPixie\Database\Conditions\Condition\Placeholder) {
            $group = $condition->conditions();
            $group = $this->parseLogic($group);

            if ($group != null) {
                $group->logic = $condition->logic;
                if ($condition->negated())
                    $group->negate();
            }

            return $group;
        }

        if ($condition instanceof \PHPixie\Database\Conditions\Condition\Operator) {
            $expanded = $this->driver->expandedCondition();
            $expanded->add($condition);
            $expanded->logic = $condition->logic;

            return $expanded;
        }

        return $condition;

    }

    protected function merge($left, $right)
    {
        if ($right->logic === 'and') {
            return $left->add($right);

        } elseif ($right->logic === 'or') {
            return $left->add($right, 'or');

        } else {
            $merged = $this->driver->expandedCondition();
            $rightClone = clone $right;
            $leftClone = clone $left;

            $merged->add($left);
            $merged->add($rightClone->negate());

            $rightPart = $this->driver->expandedCondition();
            $rightPart->add($leftClone->negate());
            $rightPart->add($right);

            $merged->add($rightPart, 'or');
            $merged->logic = $left->logic;

            return $merged;
        }

    }

    public function parse($conditions)
    {
        $expanded = $this->parseLogic($conditions);
        $expanded = $this->normalize($expanded);

        if (empty($expanded))
            return array();

        $andGroups = array();
        foreach ($expanded->groups() as $group) {
            $andGroup = array();
            foreach ($group as $condition) {
                $condition = $this->operatorParser->parse($condition);
                foreach ($condition as $field => $fieldConditions) {
                    $appended = false;
                    foreach ($andGroup as $key=>$merged) {
                        if (!isset($merged[$field])) {
                            $andGroup[$key][$field] = $fieldConditions;
                            $appended = true;
                            break;
                        }
                    }
                    if (!$appended)
                        $andGroup[] = array($field => $fieldConditions);
                }
            }

            $count = count($andGroup);
            if ($count === 1) {
                $andGroup = current($andGroup);
            } else {
                $andGroup = array('$and' => $andGroup);
            }
            $andGroups[] = $andGroup;
        }

        $count = count($andGroups);
        if ($count === 1) {
            $andGroups = current($andGroups);
        } else {
            $andGroups = array('$or' => $andGroups);
        }

        return $andGroups;

    }

}
