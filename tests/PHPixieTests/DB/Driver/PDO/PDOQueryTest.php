<?php
require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/SQL/SQLQueryTest.php');
class PDOQueryTest extends SQLQueryTest
{
    protected $resultClass = '\PHPixie\DB\Driver\PDO\Result';
    protected function query($type = 'select')
    {
        return new \PHPixie\DB\Driver\PDO\Query($this->pixie->db ,$this->connection, $this->parser, null, $type);
    }

    protected function mockParser()
    {
        return $this->getMock('\PHPixie\DB\Driver\PDO\Mysql\Parser', array('parse'), array(null, null, null, null, null));
    }

    protected function mockConnection()
    {
        return $this->getMock('\PHPixie\DB\Driver\PDO\Connection', array('execute'), array(), '', null, false);
    }

}
