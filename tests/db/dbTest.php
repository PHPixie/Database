<?php

class dbTest extends PHPUnit_Framework_TestCase
{
    protected $pixie;
    protected $db;

    public function setUp()
    {
        $this->pixie = new \PHPixie\Pixie;
        $this->db = $this->pixie->db = new PHPixie\DB($this->pixie);
    }

    public function testExpr()
    {
        $expr = $this->db->expr();
        $this->assertEquals('PHPixie\DB\SQL\Expression', get_class($expr));
        $this->assertEquals('', $expr->sql);
        $this->assertEquals(array(), $expr->params);
        $expr = $this->db->expr('pixie');
        $this->assertEquals('pixie', $expr->sql);
        $this->assertEquals(array(), $expr->params);
        $expr = $this->db->expr('pixie', array('test'));
        $this->assertEquals('pixie', $expr->sql);
        $this->assertEquals(array('test'), $expr->params);
    }

    public function testOperator()
    {
        $operator = $this->db->operator('a', '=', array(1));
        $this->assertEquals('PHPixie\DB\Conditions\Condition\Operator', get_class($operator));
        $this->assertEquals('a', $operator->field);
        $this->assertEquals('=', $operator->operator);
        $this->assertEquals(array(1), $operator->values);
    }

    public function testConditionGroup()
    {
        $group = $this->db->conditionGroup();
        $this->assertEquals('PHPixie\DB\Conditions\Condition\Group', get_class($group));
    }

    public function testConditionBuilder()
    {
        $builder = $this->db->conditionBuilder();
        $this->assertEquals('PHPixie\DB\Conditions\Builder', get_class($builder));
        $this->assertAttributeEquals('=', 'default_operator', $builder);
        $builder = $this->db->conditionBuilder('>');
        $this->assertAttributeEquals('>','default_operator',$builder);
    }

    public function testBuildDriver()
    {
        foreach (array('PDO', 'Mongo') as $driverName) {
            $driver = $this->db->buildDriver($driverName);
            $this->assertEquals('PHPixie\DB\Driver\\'.$driverName, get_class($driver));
            $this->assertAttributeEquals($this->db,'db',$driver);
        }
    }

    public function testDriver()
    {
        foreach (array('PDO', 'Mongo') as $driverName) {
            $driver = $this->db->driver($driverName);
            $this->assertEquals('PHPixie\DB\Driver\\'.$driverName, get_class($driver));
            $this->assertAttributeEquals($this->db,'db',$driver);
            $driver2 = $this->db->driver($driverName);
            $this->assertEquals($driver, $driver2);
        }
    }

    public function testGet()
    {
        $this->pixie-> config = $this->getMock('\PHPixie\Config\Slice', array('slice'), array($this->pixie, null, array()));

        $slice1 = new \PHPixie\Config\Slice($this->pixie, 'db.default', array('driver' => 'PDO'));
        $slice2 = new \PHPixie\Config\Slice($this->pixie, 'db.test', array('driver' => 'PDO'));

        $this->pixie-> config
                            ->expects($this->at(0))
                            ->method('slice')
                            ->with ('db.default')
                            ->will($this->returnValue($slice1));

        $this->pixie-> config
                            ->expects($this->at(1))
                            ->method('slice')
                            ->with ('db.test')
                            ->will($this->returnValue($slice2));

        $db = $this->getMock('\PHPixie\DB', array('driver'), array($this->pixie));
        $driver = $this->getMock('\PHPixie\DB\Driver\PDO', array('build_connection'), array($db));

        $conn1 = new \stdClass;
        $conn2 = new \stdClass;

        $driver
            ->expects($this->at(0))
            ->method('build_connection')
            ->with('default', $slice1)
            ->will($this->returnValue($conn1));

        $driver
            ->expects($this->at(1))
            ->method('build_connection')
            ->with('test', $slice2)
            ->will($this->returnValue($conn2));

        $db
            ->expects($this->exactly(2))
            ->method('driver')
            ->with ('PDO')
            ->will($this->returnValue($driver));

        $this->assertEquals($conn1, $db->get());
        $this->assertEquals($conn1, $db->get());
        $this->assertEquals($conn2, $db->get('test'));
        $this->assertEquals($conn2, $db->get('test'));

    }

    public function testQuery()
    {
        $db = $this->getMock('\PHPixie\DB', array('get'), array($this->pixie));
        $conn = $this->getMock('\PHPixie\DB\Driver\PDO', array(), array(), '', null, false);
        $db
            ->expects($this->at(0))
            ->method('get')
            ->with ('default')
            ->will($this->returnValue($conn));
        $db
            ->expects($this->at(1))
            ->method('get')
            ->with ('test')
            ->will($this->returnValue($conn));

        $conn
            ->expects($this->at(0))
            ->method('query')
            ->with('select')
            ->will($this->returnValue('query1'));

        $conn
            ->expects($this->at(1))
            ->method('query')
            ->with('delete')
            ->will($this->returnValue('query2'));

        $this->assertEquals('query1', $db->query());
        $this->assertEquals('query2', $db->query('delete', 'test'));
    }
}
