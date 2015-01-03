<?php

namespace PHPixie\Database\Conditions\Builder;

class Container implements \PHPixie\Database\Conditions\Builder
{
    protected $conditions;
    protected $groupStack = array();
    protected $currentGroup;
    protected $defaultOperator;
    protected $aliases = array(
        'and' => '_and',
        'or'  => '_or',
        'xor' => '_xor',
        'not' => '_not',
    );

    public function __construct($conditions, $defaultOperator = '=') {
        $this->conditions = $conditions;
        $this->defaultOperator = $defaultOperator;
        
        $group = $this->conditions->group();
        $this->pushGroupToStack($group);
    }

    public function startConditionGroup($logic = 'and', $negate = false)
    {
        $group = $this->conditions->group();
        $this->pushGroup($logic, $negate, $group);

        return $this;
    }

    public function pushGroup($logic, $negate, $group)
    {
        $this->addCondition($logic, $negate, $group);
        $this->pushGroupToStack($group);
    }
    
    protected function pushGroupToStack($group)
    {
        $this->groupStack[] = $group;
        $this->currentGroup = $group;
    }

    public function endGroup()
    {
        if (count($this->groupStack) === 1)
            throw new \PHPixie\Database\Exception\Builder("endGroup() was called more times than expected.");

        array_pop($this->groupStack);
        $this->currentGroup = end($this->groupStack);

        return $this;
    }

    public function buildCondition($logic, $negate, $args)
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
                $this->startConditionGroup($logic, $negate);
                $callback($this);
                $this->endGroup();

                return $this;
            }else
                throw new \PHPixie\Database\Exception\Builder("If only one argument is provided it must be a callable");

        throw new \PHPixie\Database\Exception\Builder("Not enough arguments provided");
    }

    public function addOperatorCondition($logic, $negate, $field, $operator, $values)
    {
        $condition = $this->conditions->operator($field, $operator, $values);
        $this->addCondition($logic, $negate, $condition);
    }

    public function addPlaceholder($logic = 'and', $negate = false, $allowEmpty = true)
    {
        $placeholder = $this->conditions->placeholder($this->defaultOperator, $allowEmpty);
        $this->addCondition($logic, $negate, $placeholder);

        return $placeholder->container();
    }

    public function getConditions()
    {
        return $this->groupStack[0]->conditions();
    }

    public function addCondition($logic, $negate, $condition)
    {
        $this->addToGroup($this->currentGroup, $logic, $negate, $condition);
    }
    
    protected function addToGroup($group, $logic, $negate, $condition)
    {
        $condition->setLogic($logic);
        if ($negate)
            $condition->negate();
        $group->add($condition);
    }

    public function __call($method, $args)
    {
        if(!array_key_exists($method, $this->aliases))
            throw new \PHPixie\Database\Exception\Builder("Method $method does not exist.");

        return call_user_func_array(array($this, $this->aliases[$method]), $args);
    }

    public function _and()
    {
        return $this->buildCondition('and', false, func_get_args());
    }

    public function _or()
    {
        return $this->buildCondition('or', false, func_get_args());
    }

    public function _xor()
    {
        return $this->buildCondition('xor', false, func_get_args());
    }

    public function _not()
    {
        return $this->buildCondition('and', true, func_get_args());
    }

    public function andNot()
    {
        return $this->buildCondition('and', true, func_get_args());
    }

    public function orNot()
    {
        return $this->buildCondition('or', true, func_get_args());
    }

    public function xorNot()
    {
        return $this->buildCondition('xor', true, func_get_args());
    }

    public function startGroup()
    {
        return $this->startConditionGroup('and', false);
    }

    public function startAndGroup()
    {
        return $this->startConditionGroup('and', false);
    }

    public function startOrGroup()
    {
        return $this->startConditionGroup('or', false);
    }

    public function startXorGroup()
    {
        return $this->startConditionGroup('xor', false);
    }

    public function startNotGroup()
    {
        return $this->startConditionGroup('and', true);
    }

    public function startAndNotGroup()
    {
        return $this->startConditionGroup('and', true);
    }

    public function startOrNotGroup()
    {
        return $this->startConditionGroup('or', true);
    }

    public function startXorNotGroup()
    {
        return $this->startConditionGroup('xor', true);
    }
}
