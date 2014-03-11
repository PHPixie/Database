<?php
namespace PHPixieTests\DB\Driver\PDO\Adapters;

/**
 * @coversDefaultClass \PHPixie\DB\Driver\PDO\Adapter\Sqlite
 */
class SqliteTest extends \PHPixieTests\DB\Driver\PDO\AdapterTest
{
    protected $listColumnsQuery = "PRAGMA table_info('fairies')";
    protected $listColumnsColumn = 'name';
    public function setUp()
    {
        parent::setUp();
        $this->adapter = new \PHPixie\DB\Driver\PDO\Adapter\Sqlite('test', $this->connection);
    }
}
