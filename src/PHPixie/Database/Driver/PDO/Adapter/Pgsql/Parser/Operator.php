<?php

namespace PHPixie\Database\Driver\PDO\Adapter\Pgsql\Parser;

class Operator extends \PHPixie\Database\Type\SQL\Parser\Operator
{
    /**
     * Additional operators specific to PostgreSQL
     *
     * @var array
     */
    protected $additionalOperators = array(
        'compare'   => array('>>', '>>=', '<<', '<<=',),
    );

    /**
     * Operator constructor.
     *
     * @param $database
     * @param $fragmentParser
     */
    public function __construct($database, $fragmentParser)
    {
        $this->operators['compare'] = array_merge(
            $this->operators['compare'],
            $this->additionalOperators['compare']
        );

        parent::__construct($database, $fragmentParser);
    }
}
