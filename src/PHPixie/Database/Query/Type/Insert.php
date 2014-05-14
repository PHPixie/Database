<?php

namespace PHPixie\Database\Query\Type;

interface Insert extends \PHPixie\Database\Query\Items
{
    public function data($data);
    public function clearData();
    public function getData();
}
