<?php

namespace PHPixie\Database\Driver\Mongo\Parser\Group;

class ExpandedGroup extends \PHPixie\Database\Conditions\Condition\Implementation
{
    protected $groups = array();

    public function __construct($condition = null)
    {
        if ($condition !== null)
            $this->add($condition);
    }

    protected function addAnd($condition)
    {
        $newGroups = array();
        if($condition instanceof ExpandedGroup) {
            $groups = $condition->groups();
            
            if(empty($groups))
                return;
            
            foreach ($this->groups as $group) {
                foreach($groups as $conditionGroup) {
                    $newGroup = array();
                    foreach(array_merge($group, $conditionGroup) as $cond) {
                        $newGroup[] = clone $cond;
                    }
                    $newGroups[] = $newGroup;
                }
            }
            
        }else{
            foreach ($this->groups as $group) {
                $group[] = $condition;
                $newGroups[] = $group;
            }
        }
        $this->groups = $newGroups;
    }

    protected function addOr($condition)
    {
        if ($condition instanceof ExpandedGroup) {
            $this->groups = array_merge($this->groups, $condition->groups());
        } else {
            $this->groups[] = array($condition);
        }
    }

    public function add($condition, $logic = 'and')
    {
        $isExpandedGroup = $condition instanceof ExpandedGroup;
        if(!$isExpandedGroup && !(($condition instanceof \PHPixie\Database\Conditions\Condition\Field\Operator))) {
            throw new \PHPixie\Database\Exception\Parser("You can only add ExpandedGroup and Operator conditions");
        }
        
        $condition = clone $condition;
        if (empty($this->groups)) {

            if ($isExpandedGroup) {
                $this->groups = $condition->groups();
            } else {
                $this->groups[] = array($condition);
            }

        } elseif ($logic == 'and') {
            $this->addAnd($condition);
        } elseif ($logic == 'or') {
            $this->addOr($condition);
        } else {
            throw new \PHPixie\Database\Exception\Parser("You can only use 'and' and 'or' logic");
        }

        return $this;
    }

    public function negate()
    {
        $groups = array(array());
        $count = count($this->groups);
        $negated = array();

        for ($i = $count - 1; $i >= 0; $i--) {

            $group = $this->groups[$i];

            $merged = array();

            foreach ($group as $operator) {
                if (!in_array($operator, $negated, true)) {
                    $operator = clone $operator;
                    $operator->negate();
                    $negated[] = $operator;
                }

                foreach ($groups as $oldMerged) {
                    if (!$this->conditioninArray($operator, $oldMerged)) {
                        array_unshift($oldMerged, clone $operator);
                    }
                    $merged[] = $oldMerged;

                }
            }

            $groups = $this->optimize($merged);
        }
        $this->groups = $groups;

        return $this;
    }

    protected function optimize($groups)
    {
        $count = count($groups);
        $remove = array();
        for ($i = 0; $i < $count; $i++) {
            for ($j = 0; $j < $count; $j++) {

                if ($i === $j)
                    continue;

                if ($this->isSubset($groups[$i], $groups[$j])) {
                    $remove[] = $j;
                }

            }
        }

        foreach($remove as $i)
            unset($groups[$i]);

        return array_values($groups);
    }

    protected function isSubset(&$subset, &$set)
    {
        foreach($subset as $item) {
            if(!$this->conditionInArray($item, $set))
               return false;
        }

        return true;
    }

    public function groups()
    {
        return $this->groups;
    }

    public function __clone()
    {
        foreach ($this->groups as $key=>$group) {
            foreach ($group as $itemKey => $item) {
                $this->groups[$key][$itemKey] = clone $item;
            }
        }
    }

    protected function conditionInArray($condition, $set)
    {
        foreach($set as $setCondition) {
            if(
                $condition->field() === $setCondition->field() &&
                $condition->operator() === $setCondition->operator() &&
                $condition->isNegated() === $setCondition->isNegated()
            )
                return true;
        }
        
        return false;
    }
    
    public function operatorConditions()
    {
        $conditions = array();
        foreach ($this->groups as $group) {
            foreach ($group as $item) {
                $conditions[] = $item;
            }
        }
        return $conditions;
    }
}
