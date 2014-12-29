<?php

namespace PHPixie\Database\Type;

class SQL
{
    public function expression($sql, $params = array())
    {
        return new SQl\Expression($sql, $params);
    }
}