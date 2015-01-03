<?php

namespace PHPixie\Database\Type\SQL\Query\Type;

interface Select extends \PHPixie\Database\Type\SQL\Query\Items, \PHPixie\Database\Query\Type\Select
{

    public function groupBy($field);
    public function clearGroupBy();
    public function getGroupBy();

    public function union($query, $all = false);
    public function clearUnions();
    public function getUnions();
    
    public function getHavingContainer();
    public function getHavingConditions();
    
    public function addHavingOperatorCondition($logic, $negate, $field, $operator, $values);
    public function addHavingPlaceholder($logic = 'and', $negate = false, $allowEmpty = true);
    public function startHavingConditionGroup($logic = 'and', $negate = false);
    public function buildHavingCondition($logic, $negate, $args);
    public function addHavingCondition($logic, $negate, $condition);
    
    public function having();
    public function andHaving();
    public function orHaving();
    public function xorHaving();
    public function havingNot();
    public function andHavingNot();
    public function orHavingNot();
    public function xorHavingNot();
    public function startHavingGroup();
    public function startAndHavingGroup();
    public function startOrHavingGroup();
    public function startXorHavingGroup();
    public function startHavingNotGroup();
    public function startAndHavingNotGroup();
    public function startOrHavingNotGroup();
    public function startXorHavingNotGroup();
    public function endHavingGroup();
}
