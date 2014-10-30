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
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
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

    /**
     * @covers ::<protected>
     * @covers ::get
     */
    public function testGetFirst()
    {
        $this->assertEquals(1, $this->result->get());
    }
    
    /**
     * @covers ::<protected>
     * @covers ::getItemField
     * @covers \PHPixie\Database\Result::getItemField
     */
    public function testGetFirstItemField()
    {
        $this->assertEquals(1, $this->result->getItemField((object)array('a'=>1,'b'=>2)));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::getField
     * @covers \PHPixie\Database\Result::getField
     */
    public function testGetFirstField()
    {
        $this->assertEquals(array(1, null, 3), $this->result->getField());
    }

    /**
     * @covers ::<protected>
     * @covers ::getField
     * @covers \PHPixie\Database\Result::getField
     */
    public function testGetFirstFieldNulls()
    {
        $this->assertEquals(array(1, 3), $this->result->getField(null, true));
    }
}
