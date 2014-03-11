<?php

namespace PHPixie\DB\Driver;

class Mongo extends \PHPixie\DB\Driver
{
    public function buildConnection($connectionName, $config)
    {
        return new \PHPixie\DB\Driver\Mongo\Connection($this, $connectionName, $config);
    }

    public function buildParserInstance($connectionName)
    {
        $connection      = $this->db->get($connectionName);
        $config          = $connection->config();
        $operatorParser = $this->operatorParser();
        $groupParser    = $this->groupParser($operatorParser);

        return $this->buildParser($config, $groupParser);
    }

    public function buildParser($config, $groupParser)
    {
        return new \PHPixie\DB\Driver\Mongo\Parser($this->db, $this, $config, $groupParser);
    }

    public function operatorParser()
    {
        return new \PHPixie\DB\Driver\Mongo\Parser\Operator;
    }

    public function groupParser($operatorParser)
    {
        return new \PHPixie\DB\Driver\Mongo\Parser\Group($this, $operatorParser);
    }

    public function buildQuery($connection, $parser, $config, $type)
    {
        return new \PHPixie\DB\Driver\Mongo\Query($this->db, $this->db->conditions(), $connection, $parser, $config, $type);
    }

    public function result($cursor)
    {
        return new \PHPixie\DB\Driver\Mongo\Result($cursor);
    }

    public function expandedCondition($condition = null)
    {
        return new \PHPixie\DB\Driver\Mongo\Conditions\Condition\Expanded($condition);
    }

    public function runner()
    {
        return new \PHPixie\DB\Driver\Mongo\Query\Runner;
    }

}
