<?php

namespace PHPixie\Database\Query;

interface Items extends \PHPixie\Database\Query
{
    public function limit($limit);

    public function getLimit();

    public function offset($offset);

    public function getOffset();
    
    public function orderAscendingBy($field);

    public function orderDescendingBy($field);

    public function getOrderBy();

    public function getWhereBuilder();

    public function getWhereConditions();

    public function where();

    public function orWhere();

    public function xorWhere();

    public function whereNot();

    public function orWhereNot();

    public function xorWhereNot();
    
    public function startWhereGroup();

    public function startOrWhereGroup();

    public function startXorWhereGroup();

    public function startWhereNotGroup();

    public function startOrWhereNotGroup();

    public function startXorWhereNotGroup();

    public function endWhereGroup();

    public function _and();

    public function _or();

    public function _xor();

    public function _andNot();

    public function _orNot();

    public function _xorNot();

    public function startGroup();
    
    public function startOrGroup();
    
    public function startXorGroup();
        
    public function startNotGroup();
        
    public function startOrNotGroup();
    
    public function startXorNotGroup();
    
    public function endGroup();

}