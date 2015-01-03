<?php

namespace PHPixie\Database\Query;

interface Items extends \PHPixie\Database\Query, \PHPixie\Database\Conditions\Builder
{
    public function limit($limit);
    public function clearLimit();
    public function getLimit();

    public function offset($offset);
    public function clearOffset();
    public function getOffset();

    public function orderAscendingBy($field);
    public function orderDescendingBy($field);
    public function clearOrderBy();
    public function getOrderBy();


    public function getWhereContainer();
    public function getWhereConditions();
    public function buildWhereCondition($logic, $negate, $params);
    public function addWhereCondition($logic, $negate, $condition);
    public function addWhereOperatorCondition($logic, $negate, $field, $operator, $values);
    public function addWherePlaceholder($logic = 'and', $negate = false, $allowEmpty = true);
    public function startWhereConditionGroup($logic = 'and', $negate = false);
    
    public function where();
    public function andWhere();
    public function orWhere();
    public function xorWhere();
    public function whereNot();
    public function andWhereNot();
    public function orWhereNot();
    public function xorWhereNot();
    public function startWhereGroup();
    public function startAndWhereGroup();
    public function startOrWhereGroup();
    public function startXorWhereGroup();
    public function startWhereNotGroup();
    public function startAndWhereNotGroup();
    public function startOrWhereNotGroup();
    public function startXorWhereNotGroup();
    public function endWhereGroup();

}
