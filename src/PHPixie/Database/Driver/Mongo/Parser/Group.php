<?php

namespace PHPixie\Database\Driver\Mongo\Parser;

use \PHPixie\Database\Type\Document\Conditions\Condition as DocumentCondition;

class Group extends \PHPixie\Database\Conditions\Logic\Parser
{
    protected $driver;
    protected $conditions;
    protected $operatorParser;

    public function __construct($driver, $conditions, $operatorParser)
    {
        $this->driver = $driver;
        $this->conditions = $conditions;
        $this->operatorParser = $operatorParser;
    }

    protected function normalize($condition, $prefix = null)
    {
        if($condition instanceof \PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded\SubarrayItem) {
            $conditions = $condition->conditions();
            $parsed = $this->parse($conditions);
            
            $field = $this->prefixField($prefix, $condition->field());
            $operatorCondition = $this->conditions->operator($field, 'elemMatch', array($parsed));
            $this->copyLogicAndNegated($condition, $operatorCondition);
            
            return $this->normalizeOperatorCondition($operatorCondition);
        }
        
        if($condition instanceof \PHPixie\Database\Conditions\Condition\Collection) {
            $conditions = $condition->conditions();
            
            $collectionPrefix = $prefix;
            
            if($condition instanceof \PHPixie\Database\Type\Document\Conditions\Condition\Collection\Embedded\Subdocument) {
                $collectionPrefix = $this->prefixField($prefix, $condition->field());
            }
            
            $expanded = $this->parseLogic($conditions);
            
            var_dump(111111111111111);
            foreach ($expanded->groups() as $expandedGroup) {
                foreach ($expandedGroup as $expandedCondition) {
                    if($expandedCondition instanceof \PHPixie\Database\Conditions\Condition\Field) {
                        print_r([$collectionPrefix, $expandedCondition->field()]);
                        $expandedCondition->setField($this->prefixField($collectionPrefix, $expandedCondition->field()));
                    }
                }
            }
            
            return $this->copyLogicAndNegated($condition, $expanded);
        }
        
        if ($condition instanceof \PHPixie\Database\Conditions\Condition\Field\Operator) {
            return $this->normalizeOperatorCondition($condition);
        }

        return $condition;
        
    }
    
    protected function prefixField($prefix, $field)
    {
        if($prefix === null)
            return $field;
        return $prefix.'.'.$field;
    }
        
    protected function normalizeOperatorCondition($condition)
    {
        $expanded = $this->driver->expandedCondition($condition);
        $expanded->setLogic($condition->logic());
        
        return $expanded;
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
    
    protected function parseLogic($conditions)
    {
        $expanded = parent::parseLogic($conditions);
        
        if ($expanded === null)
            $expanded = $this->driver->expandedCondition();
        
        return $expanded;
    }

    public function parse($conditions)
    {
        $expanded = $this->parseLogic($conditions);

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
        
        } elseif($count > 1) {
            $andGroups = array('$or' => $andGroups);
        }

        return $andGroups;

    }
    
    protected function copyLogicAndNegated($source, $target)
    {
        $target->setLogic($source->logic());
        $target->setIsNegated($source->isNegated());
        
        return $target;
    }

}
