<?php

namespace PHPixie\Database\Query;

interface Items extends \PHPixie\Database\Query
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
    
    public function getWhereBuilder();
    public function getWhereConditions();
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
    public function endGroup();

}