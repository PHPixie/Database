<?php

namespace PHPixie\DB\PDO;

class Conditions extends PHPixie\DB\Conditions {
	
	protected function operators() {
		return array(
			'Operator' => array('<', '<=', '=', '>=', '>'),
			'Like' => 'like',
			'Between' => 'between'
		);
	}
}