<?php

namespace PHPixie\Database\SQL;

interface class Query extends \PHPixie\Database\Query
{
    public function table($table, $alias = null);
    public function getTable();
}