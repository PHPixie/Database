<?php

namespace PHPixie\Database\SQL\Query\Type;

interface Insert extends \PHPixie\Database\SQL\Query\Items, \PHPixie\Database\Query\Type\Insert
{
    public function batchData($columns, $rows);
}