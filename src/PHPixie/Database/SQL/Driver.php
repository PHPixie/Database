<?php

namespace PHPixie\Database\SQL;

abstract class Driver extends \PHPixie\Database\Driver
{
    public function bulkData($columns, $rows)
    {
        return new Query\Data\Bulk($columns, $rows);
    }
}