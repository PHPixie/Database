<?php
namespace PHPixieTests\DB\Driver\PDO\Adapter\Pgsql\Parser;

/**
 * @coversDefaultClass \PHPixie\DB\Driver\PDO\Adapter\Pgsql\Parser\Group
 */
class GroupTest extends \PHPixieTests\DB\SQL\Parser\GroupTest
{
    protected $expected = array(
        array('"a" = ?', array(1)),
        array('"a" = ? OR "b" = ? XOR NOT "c" = ?', array(1, 1, 1)),
        array('"a" = ? OR ( "b" = ? OR "c" = ? ) XOR NOT ( "d" = ? AND "e" = ? )', array(1, 1, 1, 1, 1)),
    );

    public function setUp()
    {
        parent::setUp();
        $fragmentParser = $this->db->driver('PDO')->fragmentParser('Pgsql');
        $operatorParser = $this->db->driver('PDO')->operatorParser('Pgsql', $fragmentParser);
        $this->groupParser = $this->db->driver('PDO')->groupParser('Pgsql', $operatorParser);
    }

}
