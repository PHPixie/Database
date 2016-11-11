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
        $this->adapter = new \PHPixie\Database\Driver\PDO\Adapter\Mysql('test', $this->connection);
    }

}
