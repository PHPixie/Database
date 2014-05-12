<?php

namespace PHPixieTests;

abstract class AbstractDatabaseTest extends \PHPUnit_Framework_TestCase
{
    protected function quickMock($class, $methods)
    {
        return $this->getMock($class, $methods, array(), '', false);
    }

    protected function sliceStub($data = array())
    {
        $slice = $this->quickMock('\PHPixie\Config\Slice', array('slice', 'get', 'set', 'remove'));
        $slice
            ->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function ($key, $default = null) use ($data) {
                if(isset($data[$key]))

                    return $data[$key];
                return $default;
            }));

        return $slice;
    }

    protected function expectCalls($mock, $with, $will = array())
    {
        $at = 0;
        $methods = array_merge(array_keys($with), array_keys($will));
        $methods = array_unique($methods);
        foreach ($methods as $methodName) {
            $method = $mock
                        ->expects($this->at($at++))
                        ->method($methodName);
            if(array_key_exists($methodName, $with))
                $method = call_user_func_array(array($method, 'with'), $with[$methodName]);
            if(array_key_exists($methodName, $will))
                $method->will($this->returnValue($will[$methodName]));
        }
    }
}
