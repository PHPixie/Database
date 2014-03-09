<?php
namespace PHPixieTests\DB\Driver\Mongo;

class MongoResultCursorStub extends \ArrayObject
{
}

/**
 * @coversDefaultClass \PHPixie\DB\Driver\Mongo\Result
 */
class ResultTest extends \PHPixieTests\DB\ResultTest
{
    public function setUp()
    {
        $cursor = new MongoResultCursorStub(array(
            (object) array('id' => 1, 'name' => 'Tinkerbell'),
            (object) array('id' => null, 'name' => null),
            (object) array('id' => 3, 'name' => 'Trixie')
        ));
        $cursor = $cursor->getIterator();
        $this->result = new \PHPixie\DB\Driver\Mongo\Result($cursor);
    }

    /**
     * @covers ::rewind
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

}
