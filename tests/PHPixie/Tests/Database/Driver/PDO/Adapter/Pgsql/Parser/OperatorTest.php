<?php
namespace PHPixie\Tests\Database\Driver\PDO\Adapter\Pgsql\Parser;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Adapter\Pgsql\Parser\Operator
 */
class OperatorTest extends \PHPixie\Tests\Database\Type\SQL\Parser\OperatorTest
{
    /**
     * List of expected results from parser
     *
     * @var array
     */
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
        array('a + b = ?', array(1)),
        array('"a" >> ?', array(1)),
        array('"a" >>= ?', array(1)),
        array('"a" << ?', array(1)),
        array('"a" <<= ?', array(1)),
    );

    /**
     * @inheritdoc
     */
    protected function conditions()
    {
        return array_merge(
            parent::conditions(),
            array(
                $this->operator('a', '>>', array(1)),
                $this->operator('a', '>>=', array(1)),
                $this->operator('a', '<<', array(1)),
                $this->operator('a', '<<=', array(1)),
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();
        $fragment = $this->database->driver('pdo')->fragmentParser('Pgsql');
        $this->operatorParser = $this->database->driver('pdo')->operatorParser('Pgsql', $fragment);
    }
}
