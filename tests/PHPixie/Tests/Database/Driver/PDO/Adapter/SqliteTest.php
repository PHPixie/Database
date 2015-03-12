<?php
namespace PHPixie\Tests\Database\Driver\PDO\Adapter;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Adapter\Sqlite
 */
class SqliteTest extends \PHPixie\Tests\Database\Driver\PDO\AdapterTest
{
    protected $listColumnsQuery = "PRAGMA table_info('fairies')";
    protected $listColumnsColumn = 'name';
    public function setUp()
    {
        parent::setUp();
        $this->adapter = new \PHPixie\Database\Driver\PDO\Adapter\Sqlite('test', $this->connection);
    }
}
