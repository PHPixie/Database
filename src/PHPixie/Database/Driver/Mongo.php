<?php

namespace PHPixie\Database\Driver;

class Mongo extends \PHPixie\Database\Driver
{
    protected $conditionsParser;

    public function buildConnection($connectionName, $config)
    {
        return new \PHPixie\Database\Driver\Mongo\Connection($this, $connectionName, $config);
    }

    public function buildParserInstance($connectionName)
    {
        $connection      = $this->database->get($connectionName);
        $config          = $connection->config();
        $conditionsParser     = $this->conditionsParser();

        return $this->buildParser($config, $conditionsParser);
    }

    public function buildParser($config, $conditionsParser)
    {
        return new \PHPixie\Database\Driver\Mongo\Parser($this->database, $this, $config, $conditionsParser);
    }

    public function operatorParser()
    {
        return new \PHPixie\Database\Driver\Mongo\Parser\Operator;
    }

    public function conditionsParser()
    {
        if($this->conditionsParser === null) {
            $this->conditionsParser = $this->buildConditionsParserInstance();
        }

        return $this->conditionsParser;
    }
    
    public function queryBuilder()
    {
        $documentConditions = $this->database->document()->conditions();
        $values = $this->database->values();

        return $this->buildQueryBuilder($documentConditions, $values);
    }

    public function buildConditionsParserInstance()
    {
        $conditions = $this->database->conditions();
        $operatorParser  = $this->operatorParser();
        return new \PHPixie\Database\Driver\Mongo\Parser\Conditions($this, $conditions, $operatorParser);
    }

    public function buildQuery($type, $connection, $parser, $builder)
    {
        $class = '\PHPixie\Database\Driver\Mongo\Query\Type\\'.ucfirst($type);

        return new $class($connection, $parser, $builder);
    }
    
    public function buildQueryBuilder($documentContainerBuilder, $values)
    {
        return new \PHPixie\Database\Driver\Mongo\Query\Builder($documentContainerBuilder, $values);
    }

    public function result($cursor)
    {
        return new \PHPixie\Database\Driver\Mongo\Result($cursor);
    }

    public function expandedGroup($condition = null)
    {
        return new \PHPixie\Database\Driver\Mongo\Parser\Conditions\ExpandedGroup($condition);
    }

    public function runner()
    {
        return new \PHPixie\Database\Driver\Mongo\Query\Runner;
    }

}
