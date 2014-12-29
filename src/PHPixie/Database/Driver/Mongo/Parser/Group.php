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
        if($condition instanceof \PHPixie\Database\Type\Type\Document\Conditions\Condition\Subdocument\ArrayItem) {
            $conditions = $condition->conditions();
            $parsed = $this->parse($conditions);
            
            $operatorCondition = $this->conditions->operator($condition->field, 'elemMatch', array($parsed));
            return $this->copyLogicAndNegated($condition, $operatorCondition);  
        }
        
        if($condition instanceof \PHPixie\Database\Type\Type\Document\Conditions\Condition\Subdocument) {
            $conditions = $condition->conditions();
            $conditions = $this->prefixConditions($conditions);
            
            $group = $this->conditions->group();
            $group->setConditions($conditions);
            return $this->copyLogicAndNegated($condition, $group);
        }
        
        if ($condition instanceof \PHPixie\Database\Conditions\Condition\Group || 
                 $condition instanceof \PHPixie\Database\Conditions\Condition\Placeholder) {
            $group = $condition->conditions();
            $group = $this->parseLogic($group);

            if ($group != null) {
                $this->copyLogicAndNegated($condition, $group);
            }

            return $group;
        }

        if ($condition instanceof \PHPixie\Database\Conditions\Condition\Operator) {
            $expanded = $this->driver->expandedCondition();
            $expanded->add($condition);
            $expanded->setLogic($condition->logic());

            return $expanded;
        }

        return $condition;

    }

    protected function merge($left, $right)
    {
        if ($right->logic() === 'and') {
            return $left->add($right);

        } elseif ($right->logic() === 'or') {
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
            $merged->setLogic($left->logic());

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
    
    protected function prefixConditions($prefix, $conditions)
    {
        foreach($conditions as $key => $condition) {
            if ($condition instanceof \PHPixie\Database\Conditions\Condition\Operator) {
                $condition->field = $prefix.'.'.$condition->field;
                
            }elseif ($condition instanceof \PHPixie\Database\Conditions\Condition\Group) {
                $conditions = $condition->conditions();
                $conditions = $this->prefixConditions($prefix, $conditions);
                $condition->setConditions($conditions);
                
            }elseif ($condition instanceof \PHPixie\Database\Type\Type\Document\Conditions\Condition\Subdocument) {
                $condition->setField($prefix.'.'.$condition->field());
            
            }elseif ($condition instanceof \PHPixie\Database\Conditions\Condition\Placeholder) {
                $conditions = $condition->conditions();
                $conditions = $this->prefixConditions($prefix, $conditions);
                $group->setConditions($conditions);
                $this->copyLogicAndNegated($condition, $group);
                $conditions[$key] = $group;
            }
        }
    }
    
    protected function copyLogicAndNegated($source, $target)
    {
        $target->setLogic($source->logic());
        if($source->negated())
            $target->negate();
        
        return $target;
    }

}
