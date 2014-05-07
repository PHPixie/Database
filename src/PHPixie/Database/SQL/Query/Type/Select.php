<?php

namespace PHPixie\Database\SQL\Query\Type;

interface Select extends \PHPixie\Database\SQL\Query\Items, \PHPixie\Database\Query\Type\Select
{
    public function groupBy($field);
    public function getGroupBy();
    public function union($query);
    public function getUnions();
    
    public function having();
    public function orHaving();
    public function xorHaving();
    public function onNot();
    public function orHavingNot();
    public function xorHavingNot();
    public function startHavingGroup();
    public function startOrHavingGroup();
    public function startXorHavingGroup();
    public function startOrNotGroup();
    public function startOrHavingNotGroup();
    public function startXorHavingNotGroup();
    public function endHavingGroup();
}