<?php

namespace PHPixie\DB\Driver;

class PDO extends \PHPixie\DB\Driver
{
    public function buildConnection($connectionName, $config)
    {
        return new \PHPixie\DB\Driver\PDO\Connection($this, $connectionName, $config);
    }

    public function buildParserInstance($connectionName)
    {
        $connection      = $this->db->get($connectionName);
        $adapterName    = $connection->adapterName();
        $config          = $connection->config();
        $fragmentParser = $this->fragmentParser($adapterName);
        $operatorParser = $this->operatorParser($adapterName, $fragmentParser);
        $groupParser    = $this->groupParser($adapterName, $operatorParser);

        return $this->buildParser($adapterName, $config, $fragmentParser, $groupParser);
    }

    public function adapter($name, $config, $connection)
    {
        $class = '\PHPixie\DB\Driver\PDO\Adapter\\'.$name;

        return new $class($config, $connection);
    }

    public function buildParser($adapterName, $config, $fragmentParser, $groupParser)
    {
        $class = '\PHPixie\DB\Driver\PDO\Adapter\\'.$adapterName.'\Parser';
        return new $class($this->db, $this, $config, $fragmentParser, $groupParser);
    }

    public function fragmentParser($adapterName)
    {  
        $class = '\PHPixie\DB\Driver\PDO\Adapter\\'.$adapterName.'\Parser\Fragment';
        return new $class;
    }

    public function operatorParser($adapterName, $fragmentParser)
    {
        $class = '\PHPixie\DB\Driver\PDO\Adapter\\'.$adapterName.'\Parser\Operator';
        return new $class($this->db, $fragmentParser);
    }

    public function groupParser($adapterName, $operatorParser)
    {
        $class = '\PHPixie\DB\Driver\PDO\Adapter\\'.$adapterName.'\Parser\Group';
        return new $class($this->db, $operatorParser);
    }

    public function buildQuery($connection, $parser, $config, $type)
    {
        return new \PHPixie\DB\Driver\PDO\Query($this->db, $this->db->conditions(), $connection, $parser, $config, $type);
    }

    public function result($statement)
    {
        return new \PHPixie\DB\Driver\PDO\Result($statement);
    }

}
