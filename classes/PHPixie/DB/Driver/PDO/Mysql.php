<?php

namespace PHPixie\DB\Driver\PDO;

class Mysql extends
{
    public function parser($config)
    {
        $class = '\PHPixie\DB\Driver\PDO\\'.$adapter.'\Parser';

        return new $class($this);
    }

    public function operatorParser($config)
    {
        $class = '\PHPixie\DB\Driver\PDO\\'.$adapter.'\Parser\Operator';

        return new $class($this);
    }
}
