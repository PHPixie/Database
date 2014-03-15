<?php
namespace PHPixieTests\Database\Driver\PDO;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\PDO\Result
 */
class ResultTest extends \PHPixieTests\Database\ResultTest
{
    protected $statement;
    
    public function setUp()
    {
        $database = new \PDO('sqlite::memory:');
        $database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $database->exec("CREATE TABLE fairies(id INT,name VARCHAR(255))");
        $database->exec("INSERT INTO fairies (id,name) VALUES (1,'Tinkerbell')");
        $database->exec("INSERT INTO fairies (id,name) VALUES (NULL, NULL)");
        $database->exec("INSERT INTO fairies (id,name) VALUES (3,'Trixie')");
        $this->statement = $database->prepare("SELECT * from fairies");
        $this->statement->execute();
        $this->result = new \PHPixie\Database\Driver\PDO\Result($this->statement);
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
