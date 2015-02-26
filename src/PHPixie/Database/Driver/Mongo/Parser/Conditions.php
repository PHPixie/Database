<?php

namespace PHPixie\Database\Driver\Mongo\Parser;

use \PHPixie\Database\Type\Document\Conditions\Condition as DocumentCondition;

class Conditions extends \PHPixie\Database\Conditions\Logic\Parser
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
            
            foreach ($expanded->operatorConditions() as $operatorCondition) {  
                $operatorCondition->setField($this->prefixField($collectionPrefix, $operatorCondition->field()));
            }
            
            return $this->copyLogicAndNegated($condition, $expanded);
        }
        
        if ($condition instanceof \PHPixie\Database\Conditions\Condition\Field\Operator) {
            return $this->normalizeOperatorCondition($condition);
        }
        
        $class = get_class($condition);
        throw new \PHPixie\Database\Exception\Parser("Condition of type '$class' is not supprted");
        
    }
    
    protected function prefixField($prefix, $field)
    {
        if($prefix === null)
            return $field;
        return $prefix.'.'.$field;
    }
        
    protected function normalizeOperatorCondition($condition)
    {
        $expanded = $this->driver->expandedGroup($condition);
        $expanded->setLogic($condition->logic());
        
        return $expanded;
    }
    
    protected function merge($left, $right)
    {
        if ($right->logic() === 'and') {
            return $left->add($right);

        } elseif ($right->logic() === 'or') {
            $p = $left->add($right, 'or');
            return $p;

        } else {
            $merged = $this->driver->expandedGroup();
            $rightClone = clone $right;
            $leftClone = clone $left;

            $merged->add($left);
            $merged->add($rightClone->negate());

            $rightPart = $this->driver->expandedGroup();
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
            $expanded = $this->driver->expandedGroup();
        
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

        return (object) $andGroups;

    }
    
    protected function copyLogicAndNegated($source, $target)
    {
        $target->setLogic($source->logic());
        $target->setIsNegated($source->isNegated());
        
        return $target;
    }

}
