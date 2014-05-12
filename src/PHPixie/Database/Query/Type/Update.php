<?php

namespace PHPixie\Database\Query\Type;

interface Update extends \PHPixie\Database\Query\Items{
    public function set($values);
    public function clearSet();
    public function getSet();
}