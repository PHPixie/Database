<?php

namespace PHPixie\Database\SQL\Query\Type;

interface Update extends \PHPixie\Database\SQL\Query\Items, \PHPixie\Database\Query\Type\Update
{
    public function increment($increments);
    public function clearIncrement();
    public function getIncrement();
}
