<?php
namespace PHPixieTests\DB\Driver\PDO\Adapters;

/**
 * @coversDefaultClass \PHPixie\DB\Driver\PDO\Adapter\Mysql
 */
class MysqlTest extends \PHPixieTests\DB\Driver\PDO\AdapterTest
{
    protected $listColumnsQuery = 'DESCRIBE `fairies`';
    protected $listColumnsColumn = 'Field';
    public function setUp()
    {
        parent::setUp();
        $this->connection
                        ->expects($this->at(0))
                        ->method('execute')
                        ->with('SET NAMES utf8')
                        ->will($this->returnValue(null));
        $this->adapter = new \PHPixie\DB\Driver\PDO\Adapter\Mysql('test', $this->connection);
    }
}
