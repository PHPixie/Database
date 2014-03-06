<?php

abstract class ResultTest extends PHPUnit_Framework_TestCase
{
    protected $result;

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

    public function testNext()
    {
        $this->result->next();
        $this->result->next();
        $this->result->next();
        $this->result->next();
    }

    public function testCurrent()
    {
        $this->assertEquals(array('id' => 1, 'name' => 'Tinkerbell'), (array) $this->result->current());
        $this->result->next();
        $this->assertEquals(array('id' => 2, 'name' => null), (array) $this->result->current());
        $this->result->next();
        $this->assertEquals(array('id' => 3, 'name' => 'Trixie'), (array) $this->result->current());
        $this->result->next();
        $this->assertEquals(null, $this->result->current());
        $this->result->next();
        $this->assertEquals(null, $this->result->current());
    }

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

    public function testAsArray()
    {
        $expected = array(
            array('id' => 1, 'name' => 'Tinkerbell'),
            array('id' => 2, 'name' => null),
            array('id' => 3, 'name' => 'Trixie')
        );

        $arr = $this->result->asArray();
        foreach($arr as $key => $row)
            foreach($expected[$key] as $column=>$value)
                $this->assertEquals($value, $row->$column);
    }

    public function testGet()
    {
        $this->assertEquals(1, $this->result->get('id'));
        $this->result->next();
        $this->assertEquals(2, $this->result->get('id'));
        $this->result->next();
        $this->assertEquals(3, $this->result->get('id'));
        $this->result->next();
        $this->assertEquals(null, $this->result->get('id'));
        $this->result->next();
        $this->assertEquals(null, $this->result->get('id'));
    }

    public function testGetColumn()
    {
        $this->assertEquals(array('Tinkerbell', null, 'Trixie'), $this->result->getColumn('name'));
    }

    public function testGetColumnNulls()
    {
        $this->assertEquals(array('Tinkerbell',  'Trixie'), $this->result->getColumn('name', true));
    }

    protected function assertRewindException()
    {
        $this->result->current();
        $this->result->rewind();
        $except = false;
        $this->result->next();
        try {
            $this->result->rewind();
        } catch (\PHPixie\DB\Exception $e) {
            $except = true;
        }
        $this->assertEquals(true, $except);
    }
}
