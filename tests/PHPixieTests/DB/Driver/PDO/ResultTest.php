<?php
namespace PHPixieTests\DB\Driver\PDO;

/**
 * @coversDefaultClass \PHPixie\DB\Driver\PDO\Result
 */
class ResultTest extends \PHPixieTests\DB\ResultTest
{
    protected $statement;
    
    public function setUp()
    {
        $db = new \PDO('sqlite::memory:');
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $db->exec("CREATE TABLE fairies(id INT,name VARCHAR(255))");
        $db->exec("INSERT INTO fairies (id,name) VALUES (1,'Tinkerbell')");
        $db->exec("INSERT INTO fairies (id,name) VALUES (NULL, NULL)");
        $db->exec("INSERT INTO fairies (id,name) VALUES (3,'Trixie')");
        $this->statement = $db->prepare("SELECT * from fairies");
        $this->statement->execute();
        $this->result = new \PHPixie\DB\Driver\PDO\Result($this->statement);
    }

    /**
     * @covers ::rewind
     */
    public function testRewind()
    {
        $this->result->rewind();
        $this->assertRewindException();
    }

    /**
     *.@covers ::next
     *.@covers ::checkFetched
     *.@covers ::fetchNext
     */
    public function testNext()
    {
        parent::testNext();
    }
    
    /**
     * @covers ::statement
     */
    public function testStatement()
    {
        $this->assertEquals($this->statement, $this->result->statement());
    }
}
