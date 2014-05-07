<?php

namespace PHPixie\Database\Driver\Mongo;

interface Query extends \PHPixie\Database\Query
{
    public function collection($collection);
    public function getCollection();
}
