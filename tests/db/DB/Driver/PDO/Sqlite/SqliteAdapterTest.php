<?php

require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/Driver/PDO/PDOAdapterTest.php');

class SqliteAdapterTest extends PDOAdapterTest
{
    protected $listColumnsQuery = "PRAGMA table_info('fairies')";
    protected $listColumnsColumn = 'name';
    public function setUp()
    {
        parent::setUp();
        $this->adapter = new \PHPixie\DB\Driver\PDO\Sqlite\Adapter('test', $this->connection);
    }
}
