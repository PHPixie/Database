<?php
namespace PHPixieTests\Database\Driver\PDO\Adapter\Sqlite\Parser;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Adapter\Sqlite\Parser\Operator
 */
class OperatorTest extends \PHPixieTests\Database\Type\SQL\Parser\OperatorTest
{
    protected $expected = array(
        array('"a" = ?', array(1)),
        array('"a" = la', array()),
        array('"a" = la', array()),
        array('"a" = "b"', array()),
        array('"a" IS NULL', array()),
        array('"a" IS NOT NULL', array()),
        array('"a" <> ?', array(1)),
        array('"a" <> "c"."b"', array()),
        array('"a" > ?', array(1)),
        array('"a" > "b"', array()),
        array('"a" LIKE ?', array('hello')),
        array('"a" REGEXP ?', array('hello')),
        array('"a" IN (?, ?)', array(1, 2)),
        array('"a" IN ( la )', array()),
        array('"a" IN ( fairy )', array(1)),
        array('"a" BETWEEN ? AND ?', array(1, 2)),
        array('"a" NOT BETWEEN ? AND ?', array(1, 2)),
        array('"a"."b" = b', array(1)),
        array('a + b = ?', array(1))
    );
    public function setUp()
    {
        parent::setUp();
        $fragment = $this->database->driver('PDO')->fragmentParser('Sqlite');
        $this->operatorParser = $this->database->driver('PDO')->operatorParser('Sqlite', $fragment);
    }
}
