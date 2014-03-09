<?php
namespace PHPixieTests\DB\Driver\PDO\Adapter\Pgsql\Parser;

/**
 * @coversDefaultClass \PHPixie\DB\Driver\PDO\Adapter\Pgsql\Parser\Fragment
 */
class FragmentTest extends \PHPixieTests\DB\SQL\Parser\FragmentTest
{
    protected $quoted = '"a"';
    protected $expectedColumns = array(
        array('"a"', array()),
        array('"a"."b"', array()),
        array('"a".*', array()),
        array('*', array())
    );

    protected $expectedTables = array(
        array('"a"', array()),
        array('"a" AS "b"', array()),
        array('( la ) AS "b"', array(1)),
        array('( fairy ) AS "b"', array(1)),
    );

    protected $expectedValues = array(
        array('?', array('a')),
        array('la', array(1)),
        array('( fairy )', array(1)),
    );

    public function setUp()
    {
        parent::setUp();
        $this->fragmentParser = $this->db->driver('PDO')->fragmentParser('Pgsql');
    }

}
