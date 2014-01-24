<?php

namespace PHPixie\DB\Conditions\Logic;

abstract class Parser {
	
	protected $logic_precedance = array(
		'and' => 2,
		'xor' => 1,
		'or'  => 0
	);
	
	protected function expand_group(&$group, $level = 0) {
		$current = current($group);
		$res = $this->normalize($current);
		
		
		while (true) {
			if (($next = next($group)) === false)
				break;
				
			if ($this->logic_precedance[$next->logic] < $level) {
				prev($group);
				break;
			}
			
			$right = $this->expand_group($group, $this->logic_precedance[$next->logic]+1);
			$res = $this->merge($res, $right);
				
			$current = $next;
		}
		
		return $res;
	}
	
	protected abstract function normalize($condition);
	protected abstract function merge($left, $right);
	
}