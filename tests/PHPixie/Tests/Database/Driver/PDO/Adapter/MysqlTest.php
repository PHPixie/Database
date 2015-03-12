<?php
namespace PHPixie\Tests\Database\Driver\PDO\Adapter;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Adapter\Mysql
 */
class MysqlTest extends \PHPixie\Tests\Database\Driver\PDO\AdapterTest
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
        $this->adapter = new \PHPixie\Database\Driver\PDO\Adapter\Mysql('test', $this->connection);
    }

}
