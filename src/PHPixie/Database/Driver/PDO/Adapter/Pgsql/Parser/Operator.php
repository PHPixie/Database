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
        'array'     => array('overlap', '&&','contains', '@>', 'contained', '<@'),
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
        $this->operators['array'] = $this->additionalOperators['array'];

        parent::__construct($database, $fragmentParser);
    }

    /**
     * Parse array related operators
     *
     * @param string $field
     * @param string $operator
     * @param mixed $values
     *
     * @return \PHPixie\Database\Type\SQL\Expression
     */
    protected function parseArray($field, $operator, $values)
    {
        $value = $this->validateArrayValue(
            (array) $this->singleValue($values, $operator)
        );

        switch ($operator) {
            case 'overlap':
                $operator = '&&';
                break;
            case 'contains':
                $operator = '@>';
                break;
            case 'contained':
                $operator = '<@';
        }

        $expr = $this->prefix($field, $operator);
        $expr->sql .= '?';
        $expr->params[] = '{' . implode(',', $value) . '}';

        return $expr;
    }

    /**
     * Validate if given array can be used in array operators
     *
     * @param array $values
     *
     * @return array
     * @throws \PHPixie\Database\Exception\Parser
     */
    protected function validateArrayValue(array $values)
    {
        foreach ($values as $val) {
            if (!is_int($val)) {
                throw new \PHPixie\Database\Exception\Parser("Currently only int values are supported in arrays.");
            }
        }

        return $values;
    }
}
