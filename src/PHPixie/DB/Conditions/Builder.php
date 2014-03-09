<?php

namespace PHPixie\DB\Conditions;

class Builder
{
    protected $conditions;
    protected $groupStack = array();
    protected $currentGroup;
    protected $defaultOperator;

    public function __construct($conditions, $defaultOperator = '=')
    {
        $this->conditions = $conditions;
        $this->defaultOperator = $defaultOperator;
        $this->pushGroup($this->conditions->group());

    }

    public function startGroup($extendedLogic = 'and', $negate = false)
    {
        $group = $this->conditions->group();
        $this->addSubgroup($extendedLogic, $negate, $group, $this->currentGroup);
        $this->pushGroup($group);

        return $this;
    }

    protected function pushGroup($group)
    {
        $this->groupStack[]=$group;
        $this->currentGroup = $group;
    }

    protected function addSubgroup($extendedLogic, $negate, $group, $parent)
    {
        switch ($extendedLogic) {
            case 'and':
            case 'or':
            case 'xor':
                $logic = $extendedLogic;
                break;
            case 'andNot':
            case 'orNot':
            case 'xorNot':
                $logic = substr($extendedLogic, 0, -3);
                $negate = !$negate;
                break;
            default:
                throw new \PHPixie\DB\Exception("Logic must be either 'and', 'or', 'xor', 'andNot' ,'orNot', 'xorNot'");
        }

        if ($negate)
            $group->negate();

        $parent->add($group, $logic);

    }

    public function endGroup()
    {
        if (count($this->groupStack) === 1)
            throw new \PHPixie\DB\Exception("endGroup() was called more times than expected.");

        array_pop($this->groupStack);
        $this->currentGroup = current($this->groupStack);

        return $this;
    }

    public function addCondition($logic, $negate, $args)
    {
        $count = count($args);
        if ($count >= 2) {
            $field = $args[0];

            if ($count === 2) {
                $operator = $this->defaultOperator;
                $values = array($args[1]);
            } else {
                $operator = $args[1];
                $values = array_slice($args, 2);
            }

            $this->addOperatorCondition($logic, $negate, $field, $operator, $values);

            return $this;
        }

        if ($count === 1)
            if (is_callable($callback = $args[0])) {
                $this->startGroup($logic, $negate);
                $callback($this);
                $this->endGroup();

                return $this;
            }else
                throw new \PHPixie\DB\Exception("If only one argument is provided it must be a callable");

        throw new \PHPixie\DB\Exception("Not enough arguments provided");
    }

    public function addOperatorCondition($logic, $negate, $field, $operator, $values)
    {
        $condition = $this->conditions->operator($field, $operator, $values);
        $this->addToCurrent($logic, $negate, $condition);
    }

    public function addPlaceholder($logic, $negate)
    {
        $placeholder = $this->conditions->placeholder($this->defaultOperator);
        $this->addToCurrent($logic, $negate, $placeholder);

        return $placeholder;
    }

    public function getConditions()
    {
        return $this->groupStack[0]->conditions();
    }

    protected function addToCurrent($logic, $negate, $condition)
    {
        if ($negate)
            $condition->negate();

        $this->currentGroup->add($condition, $logic);
    }

    public function _and()
    {
        return $this->addCondition('and', false, func_get_args());
    }

    public function _or()
    {
        return $this->addCondition('or', false, func_get_args());
    }

    public function _xor()
    {
        return $this->addCondition('xor', false, func_get_args());
    }

    public function _andNot()
    {
        return $this->addCondition('and', true, func_get_args());
    }

    public function _orNot()
    {
        return $this->addCondition('or', true, func_get_args());
    }

    public function _xorNot()
    {
        return $this->addCondition('xor', true, func_get_args());
    }
}
