<?php

namespace PHPixie\Database\Conditions\Logic;

abstract class Parser
{
    protected $logicPrecedance = array(
        'and' => 2,
        'xor' => 1,
        'or'  => 0
    );

    protected function parseLogicLevel($iterator, $level = 0)
    {
        $current = $iterator->current();
        $res = $this->normalize($current);

        while (true) {
            if ($iterator->offsetExists($iterator->key()+1)) {
                $iterator->seek($iterator->key()+1);
                $next = $iterator->current();
            } else {
                break;
            }

            if ($this->logicPrecedance[$next->logic] < $level) {
                $iterator->seek($iterator->key()-1);
                break;
            }

            $right = $this->parseLogicLevel($iterator, $this->logicPrecedance[$next->logic] + 1);

            if ($right !== null)
                $res = $this->merge($res, $right);

            $current = $next;
        }

        return $res;
    }

    protected function parseLogic($group)
    {
        if (empty($group))
            return null;

        $iterator = new \ArrayIterator($group);

        return $this->parseLogicLevel($iterator);
    }

    abstract protected function normalize($condition);
    abstract protected function merge($left, $right);

}
