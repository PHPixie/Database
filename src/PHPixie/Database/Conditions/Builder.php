<?php

namespace PHPixie\Database\Conditions;

interface Builder
{
    public function addCondition($logic, $negate, $condition);
    public function buildCondition($logic, $negate, $params);
    public function addOperatorCondition($logic, $negate, $field, $operator, $values);
    public function startConditionGroup($logic = 'and', $negate = false);
    public function endGroup();
    
    public function addPlaceholder($logic = 'and', $negate = false, $allowEmpty = true);

    public function _and();
    public function _or();
    public function _xor();
    
    public function _not();
    public function andNot();
    public function orNot();
    public function xorNot();

    public function startGroup();
    public function startAndGroup();
    public function startOrGroup();
    public function startXorGroup();
    
    public function startNotGroup();
    public function startAndNotGroup();
    public function startOrNotGroup();
    public function startXorNotGroup();
    
    public function __call($method, $args);
}