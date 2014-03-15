<?php

namespace PHPixie\Database\Driver\Mongo\Conditions\Condition;

class Expanded extends \PHPixie\Database\Conditions\Condition
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
        foreach ($this->groups as $key=>$group) {
            if ($condition instanceof Expanded) {
                foreach($condition->groups as $newGroup)
                    $newGroups[] = array_merge($group, $newGroup);
            } else {
                $group[] = $condition;
                $newGroups[] = $group;
            }
        }
        $this->groups = $newGroups;
    }

    protected function addOr($group)
    {
        if ($group instanceof Expanded) {
            $this->groups = array_merge($this->groups, $group->groups);
        } else {
            $this->groups[] = array($group);
        }

        return $this;
    }

    public function add($condition, $logic = 'and')
    {
        if (empty($this->groups)) {

            if ($condition instanceof Expanded) {
                $this->groups = $condition->groups;
            } elseif ($condition instanceof \PHPixie\Database\Conditions\Condition\Operator) {
                $this->groups[] = array($condition);
            } else {
                throw new \PHPixie\Database\Exception\Parser("You can only add Expanded and Operator conditions");
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

    public function negate($debug=false)
    {
        $groups = array(array());
        $count = count($this->groups);
        $negated = array();

        for ($i = $count - 1; $i >= 0; $i--) {

            $group = $this->groups[$i];

            $merged = array();

            foreach ($group as $operator) {
                if (!in_array($operator, $negated, true)) {
                    $operator->negate();
                    $negated[] = $operator;
                }

                foreach ($groups as $oldMerged) {
                    if (!in_array($operator, $oldMerged, true)) {
                        array_unshift($oldMerged, $operator);
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
        foreach($subset as $item)
            if (!in_array($item, $set))
                return false;

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

}
