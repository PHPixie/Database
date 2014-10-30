<?php
namespace PHPixieTests\Database\Driver\Mongo;

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Result
 */
class ResultTest extends \PHPixieTests\Database\ResultTest
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
        $this->result = new \PHPixie\Database\Driver\Mongo\Result($this->cursorStub);
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
        $this->testGetField();
    }

    /**
     * @covers ::cursor
     */
    public function testCursor()
    {
        $this->assertEquals($this->cursorStub, $this->result->cursor());
    }
    
    /**
     * @covers ::<protected>
     * @covers ::get
     */
    public function testDeepGet()
    {
        $cursorStub = new \ArrayObject(array(
            (object) array('nested' => (object) array(
                    'deep' => (object) array(
                        'deeper' => 1
                    )
                )
            ),
        ));
        $this->cursorStub = $cursorStub->getIterator();
        $this->result = new \PHPixie\Database\Driver\Mongo\Result($this->cursorStub);
        
        $this->assertEquals(1, $this->result->get('nested.deep.deeper'));
        $this->assertEquals(null, $this->result->get('nested.deep.nope'));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::getItemField
     */
    public function testDeepGetItemField()
    {
        $item = (object) array('nested' => (object) array(
                    'deep' => (object) array(
                        'deeper' => 1
                    )
                )
            );
        $this->assertEquals(1, $this->result->getItemField($item, 'nested.deep.deeper'));
        $this->assertEquals(null, $this->result->getItemField($item, 'nested.deep.nope'));
    }
}
