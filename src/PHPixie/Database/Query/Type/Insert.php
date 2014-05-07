<?php

namespace PHPixie\Database\Query\Type;

interface Select extends \PHPixie\Database\Query\Items{
    public function data($data);
    public function getData();
}