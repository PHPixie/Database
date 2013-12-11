<?php

namespace PHPixie\DB\SQL\Conditions;

class Group extends PHPixie\DB\Conditions\Group {
	public function parse() {
		$expr = $this->sql->expr();
		foreach($this->conditions as $key => list($condition, $logic)) {
			if ($key !== 0)
				$expr->sql.= ' '.strtoupper($logic).' ';
			$fragment = $condition->parse();
			$sql = $fragment->sql;
			if ($condition instanceof Group)
				$sql = "($sql)";
			$expr->sql = $sql;
			$expr->params = array_merge($expr->params, $fragment->params);
		}
		
		return $expr;
	}
}