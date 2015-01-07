<?php

namespace PHPixie\Database\Driver;

class PDO extends \PHPixie\Database\Type\SQL\Driver
{
    public function buildConnection($connectionName, $config)
    {
        return new \PHPixie\Database\Driver\PDO\Connection($this, $connectionName, $config);
    }

    public function buildParserInstance($connectionName)
    {
        $connection     = $this->database->get($connectionName);
        $adapterName    = $connection->adapterName();
        $config         = $connection->config();
        $fragmentParser = $this->fragmentParser($adapterName);
        $operatorParser = $this->operatorParser($adapterName, $fragmentParser);
        $conditionsParser    = $this->conditionsParser($adapterName, $operatorParser);

        return $this->buildParser($adapterName, $config, $fragmentParser, $conditionsParser);
    }

    public function adapter($name, $config, $connection)
    {
        $class = '\PHPixie\Database\Driver\PDO\Adapter\\'.ucfirst($name);

        return new $class($config, $connection);
    }

    public function buildParser($adapterName, $config, $fragmentParser, $conditionsParser)
    {
        $class = '\PHPixie\Database\Driver\PDO\Adapter\\'.$adapterName.'\Parser';

        return new $class($this->database, $this, $config, $fragmentParser, $conditionsParser);
    }

    public function fragmentParser($adapterName)
    {
        $class = '\PHPixie\Database\Driver\PDO\Adapter\\'.$adapterName.'\Parser\Fragment';

        return new $class;
    }

    public function operatorParser($adapterName, $fragmentParser)
    {
        $class = '\PHPixie\Database\Driver\PDO\Adapter\\'.$adapterName.'\Parser\Operator';

        return new $class($this->database, $fragmentParser);
    }

    public function conditionsParser($adapterName, $operatorParser)
    {
        $class = '\PHPixie\Database\Driver\PDO\Adapter\\'.$adapterName.'\Parser\Conditions';

        return new $class($this->database, $operatorParser);
    }

    public function buildQueryBuilder($conditions, $values)
    {
        return new \PHPixie\Database\Driver\PDO\Query\Builder($conditions, $values);
    }

    public function buildQuery($type, $connection, $parser, $builder)
    {
        $class = '\PHPixie\Database\Driver\PDO\Query\Type\\'.ucfirst($type);

        return new $class($connection, $parser, $builder);
    }

    public function result($statement)
    {
        return new \PHPixie\Database\Driver\PDO\Result($statement);
    }

}
