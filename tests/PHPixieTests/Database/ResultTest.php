<?php
namespace PHPixieTests\Database;

/**
 * @coversDefaultClass \PHPixie\Database\Result
 */
abstract class ResultTest extends \PHPixieTests\AbstractDatabaseTest
{
    protected $result;

    /**
     * @covers ::valid
     */
    public function testValid()
    {
        $this->assertEquals(true, $this->result->valid());

        $this->result->next();
        $this->assertEquals(true, $this->result->valid());

        $this->result->next();
        $this->assertEquals(true, $this->result->valid());

        $this->result->next();
        $this->assertEquals(false, $this->result->valid());
    }

    /**
     * @covers ::next
     */
    public function testNext()
    {
        $this->result->next();
        $this->result->next();
        $this->result->next();
        $this->result->next();
    }

    /**
     * @covers ::current
     */
    public function testCurrent()
    {
        $this->assertEquals(array('id' => 1, 'name' => 'Tinkerbell'), (array) $this->result->current());
        $this->result->next();
        $this->assertEquals(array('id' => null, 'name' => null), (array) $this->result->current());
        $this->result->next();
        $this->assertEquals(array('id' => 3, 'name' => 'Trixie'), (array) $this->result->current());
        $this->result->next();
        $this->assertEquals(null, $this->result->current());
        $this->result->next();
        $this->assertEquals(null, $this->result->current());
    }

    /**
     * @covers ::key
     */
    public function testKey()
    {
        $this->assertEquals(0, $this->result->key());
        $this->result->next();
        $this->assertEquals(1, $this->result->key());
        $this->result->next();
        $this->assertEquals(2, $this->result->key());
        $this->result->next();
        $this->assertEquals(null, $this->result->key());
        $this->result->next();
        $this->assertEquals(null, $this->result->key());
    }

    /**
     * @covers ::asArray
     */
    public function testAsArray()
    {
        $expected = array(
            array('id' => 1, 'name' => 'Tinkerbell'),
            array('id' => null, 'name' => null),
            array('id' => 3, 'name' => 'Trixie')
        );

        $arr = $this->result->asArray();
        foreach($arr as $key => $row)
            foreach($expected[$key] as $column=>$value)
                $this->assertEquals($value, $row->$column);
    }

    /**
     * @covers ::<protected>
     * @covers ::get
     */
    public function testGet()
    {
        $this->assertEquals(1, $this->result->get('id'));
        $this->assertEquals(null, $this->result->get('not'));
        $this->result->next();
        $this->assertEquals(null, $this->result->get('id'));
        $this->result->next();
        $this->assertEquals(3, $this->result->get('id'));
        $this->result->next();
        $this->assertEquals(null, $this->result->get('id'));
        $this->result->next();
        $this->assertEquals(null, $this->result->get('id'));
    }

    /**
     * @covers ::<protected>
     * @covers ::getItemField
     */
    public function testGetItemField()
    {
        $this->assertEquals(2, $this->result->getItemField((object)array('a'=>1,'b'=>2), 'b'));
    }
    
    /**
     * @covers ::getField
     */
    public function testGetField()
    {
        $this->assertEquals(array('Tinkerbell', null, 'Trixie'), $this->result->getField('name'));
    }

    /**
     * @covers ::getField
     */
    public function testGetFieldNoNulls()
    {
        $this->assertEquals(array('Tinkerbell',  'Trixie'), $this->result->getField('name', true));
    }

    protected function assertRewindException()
    {
        $this->setExpectedException('\PHPixie\Database\Exception');
        $this->result->next();
        $this->result->rewind();
    }
}
