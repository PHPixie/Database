<?php

namespace PHPixie\DB\Driver\Mongo\Parser;

class Group extends \PHPixie\DB\Conditions\Logic\Parser
{
    protected $driver;
    protected $operatorParser;

    public function __construct($driver, $operatorParser)
    {
        $this->driver = $driver;
        $this->operatorParser = $operatorParser;
    }

    protected function normalize($condition, $convertOperator = true)
    {
        if ($condition instanceof \PHPixie\DB\Conditions\Condition\Group) {
            $group = $condition->conditions();
            $group = $this->expandGroup($group);
            $group->logic = $condition->logic;
            if ($condition->negated())
                $group->negate();

            return $group;
        }

        if ($condition instanceof \PHPixie\DB\Conditions\Condition\Operator && $convertOperator) {
            $expanded = $this->driver->expandedCondition();
            $expanded->add($condition);
            $expanded->logic = $condition->logic;

            return $expanded;
        }

        return $condition;

    }

    protected function merge($left, $right)
    {
        if ($left instanceof \PHPixie\DB\Conditions\Condition\Operator) {
            $expanded = $this->driver->expandedCondition();
            $expanded->add($left);
            $expanded->logic = $left->logic;
            $left = $expanded;
        }

        if ($right->logic === 'and')
            return $left->add($right);

        if ($right->logic === 'or')
            return $left->add($right, 'or');

        if ($right->logic === 'xor') {

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

    public function parse($group)
    {
        if (empty($group))
            return array();

        $expanded = $this->expandGroup($group);
        $expanded = $this->normalize($expanded);

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
            } elseif ($count === 0) {
                continue;
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
