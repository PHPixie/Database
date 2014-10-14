<?php

namespace PHPixie\Database\Driver\Mongo\Conditions;

class Subdocument extends \PHPixie\Database\Conditions\Builder
{
    protected $groupParser;

    public function __construct($conditions, $groupParser)
    {
        parent::__construct($conditions, '=');
        $this->groupParser = $groupParser;
    }

    public function parse()
    {
        return $this->groupParser->parse($this->getConditions());
    }
}
