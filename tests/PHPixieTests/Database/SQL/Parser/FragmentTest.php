<?php
namespace PHPixieTests\Database\SQL\Parser;

/**
  * @coversDefaultClass \PHPixie\Database\SQL\Parser\Fragment
 */
abstract class FragmentTest extends \PHPixieTests\Database\SQL\AbstractParserTest
{
    protected $database;
    protected $fragmentParser;

    protected $expectedColumns;
    protected $expectedTables;

    public function setUp()
    {
        $this->database = new \PHPixie\Database(null);
    }

    /**
     * @covers ::quote
     */
    public function testQuote()
    {
        $this->assertEquals($this->quoted, $this->fragmentParser->quote('a'));
    }

    protected function columns()
    {
        $columns = array(
            'a',
            'a.b',
            'a.*',
            '*'
        );

        return $columns;
    }

    /**
     * @covers ::appendColumn
     */
    public function testAppendColumn()
    {
        foreach ($this->columns() as $key => $column) {
            $expr = $this->database->sqlExpression();
            $expr = $this->fragmentParser->appendColumn($column, $expr);
            $this->assertExpression($expr, $this->expectedColumns[$key]);
        }
    }

    protected function tables()
    {
        $tables = array(
            array('a', null),
            array('a', 'b'),
            array($this->database->sqlExpression('la', array(1)), 'b'),
            array($this->queryStub('fairy', array(1)), 'b')
        );

        return $tables;
    }

    /**
     * @covers ::appendTable
     */
    public function testAppendTable()
    {
        foreach ($this->tables() as $key => $table) {
            $expr = $this->database->sqlExpression();
            $expr = $this->fragmentParser->appendTable($table[0], $expr, $table[1]);
            $this->assertExpression($expr, $this->expectedTables[$key]);
        }
    }

    /**
     * @covers ::appendTable
     */
    public function testAppendTableException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception\Parser');
       $this->fragmentParser->appendTable(new \stdClass, null);
    }

    protected function values()
    {
        $values = array(
            'a',
            $this->database->sqlExpression('la', array(1)),
            $this->queryStub('fairy', array(1))
        );

        return $values;
    }

    /**
     * @covers ::appendValue
     */
    public function testAppendValue()
    {
        foreach ($this->values() as $key => $value) {
            $expr = $this->database->sqlExpression();
            $expr = $this->fragmentParser->appendValue($value, $expr);
            $this->assertExpression($expr, $this->expectedValues[$key]);
        }
    }

}
