<?php

namespace PHPixie\Database\SQL\Query;

interface Items extends \PHPixie\Database\SQL\Query, \PHPixie\Database\Query\Items
{
    public function join($table, $alias = null, $type = 'inner');
    public function getJoins();
    
    public function on();
    public function orOn();
    public function xorOn();
    public function onNot();
    public function orOnNot();
    public function xorOnNot();
    public function startOnGroup();
    public function startOrOnGroup();
    public function startXorOnGroup();
    public function startOrNotGroup();
    public function startOrOnNotGroup();
    public function startXorOnNotGroup();
    public function endOnGroup();
}
