<?php

namespace Trackpoint\PDOExtension;

use PDO;
use PDOStatement;
use DateTime;

class PDOPGStatement extends PDOStatement
{
	private $pdo;
	private array $metadata = [];

	protected function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	private function getColumnType(int $index, string $name){
		if(isset($this->metadata[$name]) == false){
			$this->metadata[$name] = $this->getColumnMeta($index)['native_type'];
		}

		return $this->metadata[$name];
	}


	private function cast(int $index, string $name, $value){
		if($value == null){
			return $value;
		}

		switch($this->getColumnType($index, $name)){
			case 'json':
			case 'jsonb':
				return json_decode($value, true);
			break;
			case 'date':
			case 'timestamp':
				return new DateTime($value);
			break;
		}

		return $value;
	}

	public function fetch($fetch_style = PDO::FETCH_ASSOC, $cursor_orientation = PDO::FETCH_ORI_NEXT, $cursor_offset = 0)
	{
		$raw   = parent::fetch($fetch_style, $cursor_orientation, $cursor_offset);
		$tuple = [];

		$i = 0;
		foreach($raw as $name => $value){
			$tuple[$name] = $this->cast($i++, $name, $value);
		}

		return $tuple;
	}
}
