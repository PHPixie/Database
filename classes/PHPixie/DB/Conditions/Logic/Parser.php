<?php

namespace PHPixie\DB\Conditions\Logic;

abstract class Parser
{
    protected $logicPrecedance = array(
        'and' => 2,
        'xor' => 1,
        'or'  => 0
    );

    protected function expandGroup(&$group, $level = 0)
    {
        $current = current($group);
        $res = $this->normalize($current);

        while (true) {
            if (($next = next($group)) === false)
                break;

            if ($this->logicPrecedance[$next->logic] < $level) {
                prev($group);
                break;
            }

            $right = $this->expandGroup($group, $this->logicPrecedance[$next->logic]+1);
            $res = $this->merge($res, $right);

            $current = $next;
        }

        return $res;
    }

    abstract protected function normalize($condition);
    abstract protected function merge($left, $right);

}
