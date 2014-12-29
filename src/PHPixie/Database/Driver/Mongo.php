<?php

namespace PHPixie\Database\Driver;

class Mongo extends \PHPixie\Database\Driver
{
    protected $groupParser;

    public function buildConnection($connectionName, $config)
    {
        return new \PHPixie\Database\Driver\Mongo\Connection($this, $connectionName, $config);
    }

    public function buildParserInstance($connectionName)
    {
        $connection      = $this->database->get($connectionName);
        $config          = $connection->config();
        $groupParser     = $this->groupParser();

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

    public function groupParser()
    {
        if($this->groupParser === null) {
            $this->groupParser = $this->buildGroupParserInstance();
        }

        return $this->groupParser;
    }

    public function buildGroupParserInstance()
    {
        $operatorParser  = $this->operatorParser();
        return new \PHPixie\Database\Driver\Mongo\Parser\Group($this, $operatorParser);
    }

    public function buildQuery($type, $connection, $parser, $builder)
    {
        $class = '\PHPixie\Database\Driver\Mongo\Query\Type\\'.ucfirst($type);

        return new $class($connection, $parser, $builder);
    }
    
    public function buildQueryBuilder($conditions, $values)
    {
        $databaseConditions = $this->database->document()->conditions();
        return new \PHPixie\Database\Driver\Mongo\Query\Builder($conditions, $databaseConditions, $values);
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
