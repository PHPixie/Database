<?php

namespace PHPixie\Database\SQL\Query\Type\Update;

interface Incrementable extends \PHPixie\Database\SQL\Query\Type\Update
{
    public function increment($increments);
    public function clearIncrement();
    public function getIncrement();
}
