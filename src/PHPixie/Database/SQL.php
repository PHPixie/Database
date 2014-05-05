<?php

namespace PHPixie\Database;

class SQL
{
    public function expression($sql = '', $params = array())
    {
        return new SQL\Expression($sql, $params);
    }
    
    public function bulkData($columns, $rows)
    {
        return new SQL\Query\Data\Bulk($columns, $rows);
    }
}