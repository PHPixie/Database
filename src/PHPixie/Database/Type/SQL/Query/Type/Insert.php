<?php

namespace PHPixie\Database\Type\SQL\Query\Type;

interface Insert extends \PHPixie\Database\Type\SQL\Query\Items, \PHPixie\Database\Query\Type\Insert
{
    public function batchData($columns, $rows);
    public function clearBatchData();
    public function getBatchData();
}
