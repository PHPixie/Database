<?php

namespace PHPixie\Database\SQL\Query;

interface Items extends \PHPixie\Database\SQL\Query, \PHPixie\Database\Query\Items
{
    public function join($table, $alias = null, $type = 'inner');
    public function clearJoins();
    public function getJoins();
    
    public function on();
    public function andOn();
    public function orOn();
    public function xorOn();
    public function onNot();
    public function andOnNot();
    public function orOnNot();
    public function xorOnNot();
    public function startOnGroup();
    public function startAndOnGroup();
    public function startOrOnGroup();
    public function startXorOnGroup();
    public function startOnNotGroup();
    public function startAndOnNotGroup();
    public function startOrOnNotGroup();
    public function startXorOnNotGroup();
    public function endOnGroup();
}
