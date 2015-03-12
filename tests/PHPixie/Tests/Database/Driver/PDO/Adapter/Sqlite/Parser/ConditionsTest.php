<?php
namespace PHPixie\Tests\Database\Driver\PDO\Adapter\Sqlite\Parser;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Adapter\Sqlite\Parser\Conditions
 */
class ConditionsTest extends \PHPixie\Tests\Database\Type\SQL\Parser\ConditionsTest
{
    protected $expected = array(
        array('"a" = ?', array(1)),
        array('"a" = ? OR "b" = ? XOR NOT "c" = ?', array(1, 1, 1)),
        array('"a" = ? OR ( "b" = ? OR "c" = ? ) XOR NOT ( "d" = ? AND "e" = ? )', array(1, 1, 1, 1, 1)),
    );

    public function setUp()
    {
        parent::setUp();
        $fragmentParser = $this->database->driver('pdo')->fragmentParser('Sqlite');
        $operatorParser = $this->database->driver('pdo')->operatorParser('Sqlite', $fragmentParser);
        $this->conditionsParser = $this->database->driver('pdo')->conditionsParser('Sqlite', $operatorParser);
    }

    protected function container()
    {
        return $this->database->driver('pdo')->conditions()->container();
    }
    
}
