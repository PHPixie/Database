<?php

namespace PHPixie\Database\Type\SQL;

interface Query extends \PHPixie\Database\Query
{
    public function table($table, $alias = null);
    public function getTable();
}
