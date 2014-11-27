<?php

namespace PHPixie\Database\Query\Type\Update;

interface Incrementable extends \PHPixie\Database\Query\Type\Update
{
    public function increment($increments);
    public function clearIncrement();
    public function getIncrement();
}
