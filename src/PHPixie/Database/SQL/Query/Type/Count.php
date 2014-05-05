<?php

namespace PHPixie\Database\SQL\Query\Type;

class Count extends \PHPixie\Database\SQL\Query\Items implements \PHPixie\Database\Query\Type\Count
{
    public function count()
    {
        $this->execute()->get('count');
    }
}