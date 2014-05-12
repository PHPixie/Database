<?php

namespace PHPixie\Database\Query\Implementation;

class Builder
{
    protected $driver;
	protected $conditions;
    protected $values = array();
    protected $arrays = array();
    protected $conditionBuilders = array();
    protected $defaultBuilder;

	
	public function __construct($conditions)
	{
		$this->conditions = $conditions;
	}
	
    public function addFields($args)
    {
        $this->addKeyValuesToArray('fields', $args, false, false);
    }
    
    public function setLimit($limit)
    {
        $this->assert(is_numeric($limit), "Limit must be a number");
        $this->setValue('limit', $limit);
    }

    public function setOffset($offset)
    {
        $this->assert(is_numeric($offset), "Offset must be a number");
        $this->setValue('offset', $offset);
    }

    public function addOrderAscendingBy($field)
    {
        $this->addToArray('orderBy', array('field' => $field, 'dir' => 'asc'));
    }
    
    public function addOrderDescendingBy($field)
    {
        $this->addToArray('orderBy', array('field' => $field, 'dir' => 'desc'));
    }
    
    public function addSet($args)
    {
        $this->builder->addKeyValuesToArray('set', $args, true);
    }
    
    public function setData($data)
    {
        $this->setValue('data', $data);
    }
    
    public function conditionBuilder($name = null)
    {
        if ($name === null) {
            $this->assert($this->defaultBuilder !== null, "None of the condition builders were used");
            return $this->defaultBuilder;
        }

        if (!isset($this->conditionBuilders[$name]))
            $this->conditionBuilders[$name] = $this->conditions->builder();

        $this->defaultBuilder = $this->conditionBuilders[$name];

        return $this->defaultBuilder;
    }

    public function getConditions($name)
    {
        if (!isset($this->conditionBuilders[$name]))
            return array();

        return $this->conditionBuilders[$name]->getConditions();
    }

    public function addCondition($args, $logic = 'and', $negate = false, $builderName = null)
    {
        $this->conditionBuilder($builderName)->addCondition($logic, $negate, $args);
    }

    public function startConditionGroup($logic = 'and', $negate = false, $builderName = null)
    {
        $this->conditionBuilder($builderName)->startConditionGroup($logic, $negate);
    }

    public function endConditionGroup($builderName = null)
    {
        $this->conditionBuilder($builderName)->endGroup();
    }
    
    public function setValue($name, $value)
    {
        $this->values[$name] = $value;
    }
    
    public function clearValue($name)
    {
        $this->values[$name] = null;
    }
    
    public function getValue($name)
    {
        if(!array_key_exists($name, $this->values))
            return null;
        
        return $this->values[$name];
    }
    
    protected function ensureArray($name)
    {
        if(!array_key_exists($this->arrays, $name))
            $this->arrays[$name] = array();
    }

    public function addToArray($name, $value)
    {
        $this->ensureArray($name);
        $this->arrays[$name][]= $value;
    }
    
    public function addValuesToArray($name, $args, $unique = false)
    {
        $values = $args[0];
        if(!is_array($values)){
            $values = array($values);
        }
        
        $this->ensureArray($name);
        foreach($values as $value)
            if(!$unique || !in_array($value, $this->arrays[$name]))
                $this->arrays[$name][]= $value;
    }
    
    public function addKeyValuesToArray($name, $args, $requireKeys = false, $firstParameterIsKey = true)
    {
        $array = $args[0];
        
        if(!is_array($array)){
            $args = func_get_args();
            $this->assert(count($args) === 2, "Only an array of keys and values or a single key value pair may be passed.");
            if($firstParameterIsKey) {
                $array = array($array => $args[1]);
            }else{
                $array = array($args[1] => $array);
            }
        }
        
        $this->ensureArray($name);
        foreach($array as $key => $value) {
            if(!is_string($key)) {
                $this->assert(!$requireKeys, "A key must be specified.");
                $this->arrays[$name][]= $value;
            }else {
                $this->arrays[$name][$key]= $value;
            }
        }
    }
    
    public function clearArray()
    {
        $this->arrays[$name] = array();
    }
    
    public function getArray()
    {
        $this->ensureArray($name);
        return $this->arrays[$name];
    }
    
    public function assert($condition, $exceptionMessage)
    {
        if(!$condition)
            throw new \PHPixie\Database\Exception\Builder($exceptionMessage);
    }

}