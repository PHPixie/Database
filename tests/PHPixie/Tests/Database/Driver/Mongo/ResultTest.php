<?php
namespace PHPixie\Tests\Database\Driver\Mongo;

class MongoIdStub
{
    protected $id;
    
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    public function __toString()
    {
        return (string) $this->id;
    }
}

/**
 * @coversDefaultClass \PHPixie\Database\Driver\Mongo\Result
 */
class ResultTest extends \PHPixie\Tests\Database\ResultTest
{
    protected $idField = '_id';
    protected $cursorStub;

    public function setUp()
    {
        $cursorStub = new \ArrayObject(array(
            (object) array('_id' => new MongoIdStub(1), 'name' => 'Tinkerbell'),
            (object) array('_id' => new MongoIdStub(2), 'name' => null),
            (object) array('_id' => new MongoIdStub(3), 'name' => 'Trixie')
        ));
        
        $this->result = new \PHPixie\Database\Driver\Mongo\Result($cursorStub);
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
        $this->assertEquals(array('_id' => 1, 'name' => 'Tinkerbell'), (array) $this->result->current());
        $this->result->next();
        $this->assertEquals(array('_id' => 2, 'name' => null), (array) $this->result->current());
        $this->result->next();
        $this->result->rewind();
        $this->assertEquals(array('_id' => 1, 'name' => 'Tinkerbell'), (array) $this->result->current());
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
        $this->assertInstanceOf('IteratorIterator', $this->result->cursor());
    }
    
    /**
     * @covers ::<protected>
     * @covers ::get
     */
    public function testDeepGet()
    {
        $cursorStub = new \ArrayObject(array(
            (object) array(
                '_id' => new MongoIdStub(1),
                'nested' => array(
                    'deep' => array(
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
        $item = (object) array('nested' => array(
                    'deep' => array(
                        'deeper' => 1
                    )
                )
            );
        $this->assertEquals(1, $this->result->getItemField($item, 'nested.deep.deeper'));
        $this->assertEquals(null, $this->result->getItemField($item, 'nested.deep.nope'));
    }
}
