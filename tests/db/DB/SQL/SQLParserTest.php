<?php
require_once(ROOT.'/vendor/phpixie/db/tests/db/DB/SQL/Parser/BaseSQLParserTest.php');

abstract class SQLParserTest extends BaseSQLParserTest{
	
	protected $pixie;
	protected $parser;
	protected $expected;
	
	protected function setUp(){
		$this->pixie = new \PHPixie\Pixie;
		$this->pixie->db = new \PHPixie\DB($this->pixie);
	}
	
	public function queries() {
		$queries = array(
			$this->query('select')->table('fairies'),
			
			$this->query('select')->table('fairies')->where('a', 1)
													->or_where(function($builder) {
														$builder->_and('b', 1)
																->_xor('c', 1);
													})->where('d', 1),
			
			$this->query('select')->table('fairies')
													->where('a', 1)
													->having('b', 1)
													->limit(7)
													->offset(9)
													->order_by('id', 'desc')
													->order_by('name', 'asc')
													->group_by('id')
													->group_by('name'),
			
			$this->query('select')
								->table($this->query('select')->table('fairies'), 'b')
								->join('pixies')
								->on('b.id', 'pixies.id')
								->union($this->query('select')->table('pixies'), true),
								
			$this->query('select')
								->table($this->pixie->db->expr('test1', array(1)), 'b')
								->join($this->pixie->db->expr('test2', array(2)), 'c', 'left_outer')
								->on('b.id', 'c.id')
								->union($this->pixie-> db->expr('test3', array(3))),
			
			$this->query('update')
								->table('fairies')
								->data(array(
									'id'   => 3,
									'name' => 'Trixie'
								))
								->join('pixies')
								->on('fairies.id', 'pixies.id')
								->where('id', 7)
								->order_by('id')
								->limit(6)
								->offset(7),
								
			$this->query('insert')
								->table('fairies')
								->data(array(
									'id'   => 3,
									'name' => 'Trixie'
								)),
								
			$this->query('delete')
								->table('fairies')
								->where('id', 7)
								->order_by('id')
								->limit(6)
								->offset(7),
			
			$this->query('count')
								->table('fairies')
								->where('a', 1)
								->limit(7)
								->offset(9)
								->order_by('id', 'desc')
								->order_by('name', 'asc'),
								
			$this->query('insert')
								->table('fairies'),
		);
		
		return $queries;
	}
	
	public function testParse() {
		foreach($this->queries() as $key => $query) {
			$parsed = $this->parser->parse($query);
			$this->assertExpression($parsed, $this->expected[$key]);
		}
	}
	
	public function testExceptions() {
		foreach($this->exception_queries() as $key=>$query) {
			$except = false;
			try {
				$parsed = $this->parser->parse($query);
			}catch (\PHPixie\DB\Exception\Parser $e) {
				$except = true;
			}
			$this->assertEquals(true, $except);
		}
	}
	
	protected function exception_queries() {
		$queries = array(
			$this->query('pixie')
								->table('fairies'),
			
			$this->query('count')
								->table('fairies')
								->group_by('id'),
								
			$this->query('count')
								->table('fairies')
								->union($this->query('select')->table('fairies')),
								
			$this->query('update')
								->data(array('id' => 1)),
								
			$this->query('insert')
								->data(array('id' => 1)),
								
			$this->query('delete')
								->where('id', 1),
								
			$this->query('count')
								->where('id', 1),
								
			$this->query('update')
								->table('fairies'),
								
			$this->query('select')
								->table('fairies')
								->join('pixies', null, 'test'),
			
			$this->query('select')
								->union('a')
			
		);
		
		return $queries;
	}
	
	protected abstract function query($type);
	
}