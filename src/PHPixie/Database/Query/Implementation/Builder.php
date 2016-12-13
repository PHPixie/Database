<?php

namespace PHPixie\Database\Query\Implementation;

abstract class Builder
{

    protected $conditions;

    protected $valueBuilder;

    /**
     * @var array
     */
    protected $values              = array();

    /**
     * @var array
     */
    protected $arrays              = array();

    /**
     * @var array
     */
    protected $conditionContainers = array();

    /**
     * @var
     */
    protected $defaultContainer;

    /**
     * Builder constructor.
     *
     * @param $conditions
     * @param $valueBuilder
     */
    public function __construct($conditions, $valueBuilder)
    {
        $this->conditions = $conditions;
        $this->valueBuilder = $valueBuilder;
    }

    /**
     * @param $args
     */
    public function addFields($args)
    {
        $this->addValuesToArray('fields', $args, true);
    }

    /**
     * @param $limit
     *
     * @throws \PHPixie\Database\Exception\Builder
     */
    public function setLimit($limit)
    {
        $this->assert(is_numeric($limit), "Limit must be a number");
        $this->setValue('limit', $limit);
    }

    /**
     * @param $offset
     *
     * @throws \PHPixie\Database\Exception\Builder
     */
    public function setOffset($offset)
    {
        $this->assert(is_numeric($offset), "Offset must be a number");
        $this->setValue('offset', $offset);
    }

    /**
     * @param $field
     * @param $direction
     */
    public function addOrderBy($field, $direction)
    {
        $this->addToArray('orderBy', $this->valueBuilder->orderBy($field, $direction));
    }

    /**
     * @param $field
     */
    public function addOrderAscendingBy($field)
    {
        $this->addToArray('orderBy', $this->valueBuilder->orderBy($field, 'asc'));
    }

    /**
     * @param $field
     */
    public function addOrderDescendingBy($field)
    {
        $this->addToArray('orderBy', $this->valueBuilder->orderBy($field, 'desc'));
    }

    /**
     * @param $args
     */
    public function addSet($args)
    {
        $this->addKeyValuesToArray('set', $args, true);
    }

    /**
     * @param $data
     *
     * @throws \PHPixie\Database\Exception\Builder
     */
    public function setData($data)
    {
        $this->assert(is_array($data), "Data must be an array");
        $this->setValue('data', $data);
    }

    /**
     * @param $name
     */
    public function clearValue($name)
    {
        $this->values[$name] = null;
    }

    /**
     * @param $name
     *
     * @return mixed|null
     */
    public function getValue($name)
    {
        if(!array_key_exists($name, $this->values))

            return null;

        return $this->values[$name];
    }

    /**
     * @param $name
     * @param $value
     */
    protected function setValue($name, $value)
    {
        $this->values[$name] = $value;
    }

    /**
     * @param $name
     */
    public function clearArray($name)
    {
        if(array_key_exists($name, $this->arrays))
            unset($this->arrays[$name]);
    }

    /**
     * @param $name
     *
     * @return array|mixed
     */
    public function getArray($name)
    {
        if(!array_key_exists($name, $this->arrays))

            return array();

        return $this->arrays[$name];
    }

    /**
     * @param $name
     */
    protected function ensureArray($name)
    {
        if(!array_key_exists($name, $this->arrays))
            $this->arrays[$name] = array();
    }

    /**
     * @param $name
     * @param $value
     */
    protected function addToArray($name, $value)
    {
        $this->ensureArray($name);
        $this->arrays[$name][]= $value;
    }

    /**
     * @param      $name
     * @param      $args
     * @param bool $unique
     */
    protected function addValuesToArray($name, $args, $unique = false)
    {
        $values = $args[0];
        if (!is_array($values)) {
            $values = array($values);
        }

        $this->ensureArray($name);
        foreach($values as $value)
            if(!$unique || !in_array($value, $this->arrays[$name]))
                $this->arrays[$name][]= $value;
    }

    /**
     * @param      $name
     * @param      $args
     * @param bool $requireKeys
     * @param bool $firstParameterIsKey
     * @param bool $assertIsNumeric
     *
     * @throws \PHPixie\Database\Exception\Builder
     */
    protected function addKeyValuesToArray($name, $args, $requireKeys = false, $firstParameterIsKey = true, $assertIsNumeric = false)
    {
        $array = $args[0];

        if (!is_array($array)) {
            $count = count($args);
            if ($count === 1) {
                $this->assert(!$requireKeys, "Either an array, a key value pair or a single value may be passed.");
                $array = array($array);
            } else {
                $this->assert($count === 2, "Only an array of keys and values or a single key value pair may be passed.");

                if ($firstParameterIsKey) {
                    $array = array($array => $args[1]);
                } else {
                    $array = array($args[1] => $array);
                }
            }
        }

        $this->ensureArray($name);
        foreach ($array as $key => $value) {
            if($assertIsNumeric)
                $this->assert(is_numeric($value), "Value must be a number");
            if (!is_string($key)) {
                $this->assert(!$requireKeys, "A key must be specified.");
                $this->arrays[$name][]= $value;
            } else {
                $this->arrays[$name][$key]= $value;
            }
        }
    }

    /**
     * @param null $name
     *
     * @return mixed
     */
    public function conditionContainer($name = null)
    {
        if ($name === null) {
            if($this->defaultContainer !== null) {
                return $this->defaultContainer;
            }else{
                $name = 'where';
            }
        }
        
        if (!array_key_exists($name, $this->conditionContainers))
            $this->conditionContainers[$name] = $this->conditions->container();
        $this->defaultContainer = $this->conditionContainers[$name];

        return $this->defaultContainer;
    }

    /**
     * @param $name
     *
     * @return array
     */
    public function getConditions($name)
    {
        if (!isset($this->conditionContainers[$name]))
            return array();

        return $this->conditionContainers[$name]->getConditions();
    }

    /**
     * @param      $logic
     * @param      $negate
     * @param      $condition
     * @param null $containerName
     */
    public function addCondition($logic, $negate, $condition, $containerName = null)
    {
        $this->conditionContainer($containerName)->addCondition($logic, $negate, $condition);
    }

    /**
     * @param      $logic
     * @param      $negate
     * @param      $args
     * @param null $containerName
     */
    public function buildCondition($logic, $negate, $args, $containerName = null)
    {
        $this->conditionContainer($containerName)->buildCondition($logic, $negate, $args);
    }

    /**
     * @param      $logic
     * @param      $negate
     * @param      $field
     * @param      $operator
     * @param      $values
     * @param null $containerName
     */
    public function addOperatorCondition($logic, $negate, $field, $operator, $values, $containerName = null)
    {
        $this->conditionContainer($containerName)->addOperatorCondition($logic, $negate, $field, $operator, $values);
    }

    /**
     * @param string $logic
     * @param bool   $negate
     * @param null   $containerName
     */
    public function startConditionGroup($logic = 'and', $negate = false, $containerName = null)
    {
        $this->conditionContainer($containerName)->startConditionGroup($logic, $negate);
    }

    /**
     * @param null $containerName
     */
    public function endConditionGroup($containerName = null)
    {
        $this->conditionContainer($containerName)->endGroup();
    }

    /**
     * @param string $logic
     * @param bool   $negate
     * @param bool   $allowEmpty
     * @param null   $containerName
     *
     * @return mixed
     */
    public function addPlaceholder($logic = 'and', $negate = false, $allowEmpty = true, $containerName = null)
    {
        return $this->conditionContainer($containerName)->addPlaceholder($logic, $negate, $allowEmpty);
    }

    /**
     * @param $condition
     * @param $exceptionMessage
     *
     * @throws \PHPixie\Database\Exception\Builder
     */
    public function assert($condition, $exceptionMessage)
    {
        if(!$condition)
            throw new \PHPixie\Database\Exception\Builder($exceptionMessage);
    }

}
