<?php

namespace PHPixie\Database;

class Values
{
    /**
     * @param $field
     * @param $direction
     *
     * @return Values\OrderBy
     */
    public function orderBy($field, $direction)
    {
        return new \PHPixie\Database\Values\OrderBy($field, $direction);
    }
}