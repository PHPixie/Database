<?php
namespace PHPixieTests\DB\Driver\Mongo;

/**
 * @coversDefaultClass \PHPixie\DB\Driver\Mongo\Result
 */
class ResultTest extends \PHPixieTests\DB\ResultTest
{
    protected $cursorStub;
    
    public function setUp()
    {
        $cursorStub = new \ArrayObject(array(
            (object) array('id' => 1, 'name' => 'Tinkerbell'),
            (object) array('id' => null, 'name' => null),
            (object) array('id' => 3, 'name' => 'Trixie')
        ));
        $this->cursorStub = $cursorStub->getIterator();
        $this->result = new \PHPixie\DB\Driver\Mongo\Result($this->cursorStub);
    }

    /**
     * @covers ::rewind
     * @covers ::__construct
     */
    public function testRewind()
    {
        $this->assertEquals(array('id' => 1, 'name' => 'Tinkerbell'), (array) $this->result->current());
        $this->result->next();
        $this->assertEquals(array('id' => null, 'name' => null), (array) $this->result->current());
        $this->result->next();
        $this->result->rewind();
        $this->assertEquals(array('id' => 1, 'name' => 'Tinkerbell'), (array) $this->result->current());
        $this->result->next();
        $this->testAsArray();
        $this->result->next();
        $this->result->next();
        $this->testGetColumn();
    }
    
    /**
     * @covers ::cursor
     */
    public function testCursor()
    {
        $this->assertEquals($this->cursorStub, $this->result->cursor());
    }

}
