<?php

namespace PHPixie\Database\Driver;

class Mongo extends \PHPixie\Database\Driver
{
    public function buildConnection($connectionName, $config)
    {
        return new \PHPixie\Database\Driver\Mongo\Connection($this, $connectionName, $config);
    }

    public function buildParserInstance($connectionName)
    {
        $connection      = $this->database->get($connectionName);
        $config          = $connection->config();
        $operatorParser = $this->operatorParser();
        $groupParser    = $this->groupParser($operatorParser);

        return $this->buildParser($config, $groupParser);
    }

    public function buildParser($config, $groupParser)
    {
        return new \PHPixie\Database\Driver\Mongo\Parser($this->database, $this, $config, $groupParser);
    }

    public function operatorParser()
    {
        return new \PHPixie\Database\Driver\Mongo\Parser\Operator;
    }

    public function groupParser($operatorParser)
    {
        return new \PHPixie\Database\Driver\Mongo\Parser\Group($this, $operatorParser);
    }

    public function buildQuery($connection, $parser, $config, $type)
    {
        return new \PHPixie\Database\Driver\Mongo\Query($this->database, $this->database->conditions(), $connection, $parser, $config, $type);
    }

    public function result($cursor)
    {
        return new \PHPixie\Database\Driver\Mongo\Result($cursor);
    }

    public function expandedCondition($condition = null)
    {
        return new \PHPixie\Database\Driver\Mongo\Conditions\Condition\Expanded($condition);
    }

    public function runner()
    {
        return new \PHPixie\Database\Driver\Mongo\Query\Runner;
    }

}
