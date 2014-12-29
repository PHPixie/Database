<?php

namespace PHPixie\Database\Driver\PDO\Adapter\Mysql\Parser;

class Fragment extends \PHPixie\Database\Type\SQL\Parser\Fragment
{
    protected $quote = '`';
}
