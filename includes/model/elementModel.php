<?php

	class elementModel extends Model {
		
		public function __construct() {
			parent::__construct();
			
			$this->elementsMainCols = array("atom_number", "atom_type", "atom_name");
			
		}
		
		
		
		public function loadElementData($n) {
			
			$name = $n;
			
			$table = "elements";
			$cols = $this->elementsMainCols;
			$where = "`atom_name` = '" . $name . "'";
			
			return $this->select($table, $cols, $where);
			
		}
		
		
		public function loadPreviousElement($n) {
			
			$number = $n;
			
			$table = "elements";
			$cols = $this->elementsMainCols;
			$where = "`atom_number` = '" . ($number - 1) . "'";
			
			return $this->select($table, $cols, $where);
			
		}
		
		
		
		public function loadNextElement($n) {
			
			$number = $n;
			
			$table = "elements";
			$cols = $this->elementsMainCols;
			$where = "`atom_number` = '" . ($number + 1) . "'";
			
			return $this->select($table, $cols, $where);
			
		}
		
		
		
		
	}

?>
