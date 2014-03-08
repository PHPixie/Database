<?php
require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/SQL/Parser/BaseSQLParserTest.php');

abstract class SQLFragmentTest extends BaseSQLParserTest
{
    protected $db;
    protected $fragmentParser;

    protected $expectedColumns;
    protected $expectedTables;

    public function setUp()
    {
        $pixie = new \PHPixie\Pixie;
        $this->db = $pixie->db = new \PHPixie\DB($pixie);
    }

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

    public function testAppendColumn()
    {
        foreach ($this->columns() as $key => $column) {
            $expr = $this->db->expr();
            $expr = $this->fragmentParser->appendColumn($column, $expr);
            $this->assertExpression($expr, $this->expectedColumns[$key]);
        }
    }

    protected function tables()
    {
        $tables = array(
            array('a', null),
            array('a', 'b'),
            array($this->db->expr('la', array(1)), 'b'),
            array($this->queryStub('fairy', array(1)), 'b')
        );

        return $tables;
    }

    public function testAppendTable()
    {
        foreach ($this->tables() as $key => $table) {
            $expr = $this->db->expr();
            $expr = $this->fragmentParser->appendTable($table[0], $expr, $table[1]);
            $this->assertExpression($expr, $this->expectedTables[$key]);
        }
    }

    protected function values()
    {
        $values = array(
            'a',
            $this->db->expr('la', array(1)),
            $this->queryStub('fairy', array(1))
        );

        return $values;
    }

    public function testAppendValue()
    {
        foreach ($this->values() as $key => $value) {
            $expr = $this->db->expr();
            $expr = $this->fragmentParser->appendValue($value, $expr);
            $this->assertExpression($expr, $this->expectedValues[$key]);
        }
    }

}
