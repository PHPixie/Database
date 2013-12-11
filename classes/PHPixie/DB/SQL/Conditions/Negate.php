<?php

namespace PHPixie\DB\SQL\Conditions;

class Negate extends PHPixie\DB\Conditions\Negate {
	public function parse() {
		$expr = $this->sql->expr();
		$fragment = $condition->parse();
		$sql = $fragment->sql;
		if ($condition instanceof Group)
			$sql="($sql)"
		$expr->sql = "NOT {$sql}"
		$expr->params = $fragment->params;
		return $expr;
	}
}