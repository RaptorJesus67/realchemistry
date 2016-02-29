<?php
	
	/**
	 * 
	 *	THE MAIN MODEL FOR THE MVC
	 * 
	 *	The model contains SQL queries for the database.
	 * 
	 */
	class Model {
		
		
		/**
		 * 
	 	 *	@access				private					[Used in the class to sanitize]
		 *	This is the variable that will be used in the controller and view
		 */
		public $table;
		
		private $data;
		private $query;
		
		/**
		 *	
		 * 	@access				private					[Used in the class to sanitize]
		 * 	Local connection for the database
		 * 
		 */
		private $clean;
		
		/**
		 *	Local connection for the database
		 */
		private $conn;
		
		
		
		
		/**
		 * 
		 *	The Model constructor
		 * 
		 * 	Initiates variables to be used in the model
		 * 
		 *	@global				$clean					[The functions for sanitizing]
		 *	@global				$conn					[Connection to the database]
		 *	@var				$
		 * 
		 */
		public function __construct() {
			
			////////////////////////////////
			//		CALL GLOBALS			//
			////////////////////////////////
			global $clean;
			global $conn;
			
			
			////////////////////////////////
			//	MAKE LOCAL VARS			  //
			////////////////////////////////
			$this->clean = $clean;
			$this->conn = $conn;
			
		}
		
		
		
		
		
		/**
		*
		*	Select Info From Database
		*
		*	Used in Sub-Model's methods to pull out of database
		*
		*	@access				protected				[used in sub-model's]
		*	@param				string					["$tableName", name of the table]
		*	@param				array					["$columns", columns to be selected]
		*	^@param				string					["$where", mysql parameters to be set]
		*	^@param				int						["$limit", integer of amount to be pulled from db]
		*	@return				boolean					[return true if table has values]
		*
		*/
		public function select($tableName, $columns = array(), $where = null, $limit = null, $order = array(), $count = FALSE, $explain = FALSE) {
			
			//////////////////////////////
			//	SQL INJECTION PROTECTION
			//
			// 		The main SQL Injector/XSS Attack
			//
			//
			global $purifier;
			
			
			//////////////////////////////
			//	EXPLAIN
			//
			//		For SQL Explain Code
			//
			//
			if ($explain) {
			
				$explain = "EXPLAIN ";
				
			} else {
				
				$explain = null;
			
			}
			
			
			//////////////////////////////
			//	COLUMNS
			//
			//		Make columns SQL friendly
			//
			//
			$cols = "";
			
			if (!empty($columns)) {
				$cols = "`";
				$cols .= implode("`, `", $columns);
				$cols .= "`";
			}
			
			
			//////////////////////////////
			//	TABLE
			//
			//		Create $table variable
			//
			//
			$table = "`" . $tableName . "`";
			
			
			
			/////////////////////////////
			//	WHERE
			//
			//		Initiate the WHERE Statement
			//
			//
			if (!empty($where)) {
				
				$where = " WHERE " . $where;
				
			}
			
			
			/////////////////////////////
			//	LIMIT
			//
			//		Check limit
			//
			//
			if (!empty($limit)) {
				
				$limit = " LIMIT $limit";
				
			}
			
			
			
			//////////////////////////////
			//	ORDER
			//
			// 		Check Order
			//
			//
			if (!empty($order)) {
				
				$ord = " ORDER BY " . $order[0] . " " . $order[1];
				
			} else {
				
				$ord = null;
				
			}
			
			
			
			//////////////////////////////
			//	COUNT
			//
			// 		Check Count
			//
			//
			if ($count) {
				
				$counts = " COUNT(`" . $count[0] . "`) AS `" . $count[1] . "`";
				
			} else {
			
				$counts = null;
				
			}
			
			
			
			///////////////////////////////
			//	SQL
			//
			// 		SQL CODE
			//
			//
			$sql = $explain . "SELECT " . $counts . $cols . " FROM " . $table . $where . $limit . $ord;
			
			
			// SQL DEBUGGING IF CODE RETURNS BOOLEAN ERROR
			#echo $sql . "<br>";
			
			
			
			
			///////////////////////////////
			//	QUERY
			//
			//		Actual Query made here with a die statement for error handling
			//
			//
			//
			$query = $this->conn->query($sql) or die ($this->conn->error);
			
			
			
			/////////////////////////////////////////////////////////////////////
			// Store the value in a variable called table with an array of that 
			// table's name followed by it's values and iteration
			//
			// EX: $model->table["bands"]["band_name"][$i]
			//
			// Accessible by the individual page/directory's controller's
			
			while($row = $query->fetch_assoc()){
				
				////////////////////////////////////////////////////////////////////////////////
				// Store values as $model->table["tableName"]["columnName"]["index (usually 0)"]
				//
				// EX: $model->table["elements"]["element_name"][0]
				//	This is for "Hydrogen", the first element
				//	
				//	Subtract 1 from 'atomic number' since computers count from '0'
				foreach ($row as $key => $val) {
					$this->data[$tableName][$key][] = $row[$key];
				}
			
			}
			
			
			
			////////////////////////////////////////////////////////////////////
			//
			// Loop through results to clean them
			// Foreach loops through each column
			// Make sure the table isn't empty (i.e. login returns an error)
			//
			//
			if (!empty($this->data[$tableName])) {
				
				foreach ($this->data[$tableName] as $tble => $column) {
					
					// For loop goes through each value in a certain row
					for ($row = 0; $row < count($column); $row++) {
						
						// Convert from data variable to table after HTML PURIFIER
						$this->table[$tableName][$tble][$row] = $purifier->purify($column[$row]);
					
					}
					
				}
				
			}
			
			
			///////////////////////////////////////////////////////////////
			// Declare the array after loop has finished for use in view
			//
			//
			$this->table;
			
			if (!empty($this->table)) {
				
				# Returns Boolean to Sub-Model That Can Be
				# Used in Sub-Controller For Access
				# And Navigation
				return true;
				
			}
			
		}
		
		
		
		
		
		
		
		
		
		/**
		*	CASE: ($column => $delimiter, $column => $delimiter)
		*			
		*	If there is a case, a group by is required or query returns false instantly.
		*/
		protected function selectByCase($table, $cols = array(), $where = null, $case = null, $caseDelim = array(), $group = null, $limit = null) {
	
			
			// If the case is empty and group isn't OR group is empty and case isn't: RETURN FALSE
			//
			// ALLOW IF BOTH ARE EMPTY OR BOTH HOLD VALUES
			if ((!empty($group) && empty($case)) || (empty($group) && !empty($case))) {
			
				return false;
				
			}
			
			
			// PURIFIER INCLUDE
			global $purifier;
			
			
			// Table
			$tableName = "`" . $table . "`";
			
			// BEGIN SELECT STATEMENT
			$select = "SELECT ";
			
			// Columns
			if (!empty($cols)) {
				$columns = "`";								# Add '`' to the beginning
				$columns .= implode("`, `", $cols);			# Convert array to string
				$columns .= "`";							# Add '`' to the ending
			} else {
				$columns = null;
			}
			
			// If $where or $case aren't empty, add 'WHERE' to the sql string
			$where = (!empty($where)) ? " WHERE " . $where : null;
			
			
			// Case order by
			if (!empty($caseDelim) && !empty($case) && is_array($caseDelim)) {
				
				/** THIS IS HOW THE QUERY SHOULD LOOK
				SELECT `value1`, `value2`,
					CASE 'name'
						WHEN `stock_ticker` LIKE 'MO%' THEN 2 
						ELSE 1
					END
				FROM
				   `stocks`
				WHERE `stock_simpleName` LIKE 'MO%'
				GROUP BY 'name'
				LIMIT 5
				
				*/
				
				$count = count($caseDelim);
				echo $count;
				
				// Columns exist
				if (!empty($cols)) {
					$comma = ",";
				} else {
					$comma = "";
				}
				
				$caseArray = " CASE " ;
				$c = 0;
				// Loop through each iteration of the case array
				foreach ($caseDelim as $delim) {
					
					// Create an iteration of each case supplied by the user
					$caseArray .= " WHEN `" . $case . "` " . $delim . " THEN " . $count--;
						
					$c++;				
				}
				
				$caseArray .= " ELSE " . 1 . " END AS name ";
				
				// Convert THe case array into a string
				$caseString = $caseArray;
				
				
			// Case doesn't load in as an array
			} else {
				$caseString = null;
				$comma = "";
			}
			
			
			// SET GROUP VARIABLE
			(!empty($group)) ? $group = " ORDER BY name DESC" : $group = null;
				
			
			// If there is a limit that is set, add it to the sql string
			$limit = (!empty($limit)) ? " LIMIT " . $limit : null;
			
			
			
			// SQL CODE
			$sql = $select . $columns . $comma . "\n" .
					$caseString . "\n" .
					" FROM " . $tableName . "\n" . 
					$where . "\n" . 
					$group . "\n" .
					$limit . "";
			
			
			
			// SQL DEBUGGING IF CODE RETURNS BOOLEAN ERROR
			#echo $sql;
			
			$query = $this->conn->query($sql) or die($this->conn->error);
			
			// Store the value in a variable called table with an array of that table's name followed by it's values
			// EX: $model->table["bands"]["band_name"]
			//
			// Accessible by the individual page/directory's controller's
			
			while($row = $query->fetch_assoc()){
				
				// Store values as $model->table["tableName"]["columnName"]["index (usually 0)"]
				foreach ($row as $key => $val) {
					$this->data[$table][$key][] = $row[$key];
				}
			
			}
			
			
			// Loop through results to clean them
			// Foreach loops through each column
			// Make sure the table isn't empty (i.e. login returns an error)
			if (!empty($this->data[$table])) {
				foreach ($this->data[$table] as $key => $tableArray) {
					
					// For loop goes through each value in a certain row
					for ($i = 0; $i < count($tableArray); $i++) {
						// Convert from data variable to table after HTML PURIFIER
						$this->table[$table][$key][$i] = $purifier->purify($tableArray[$i]);
					}
					
				}
			}
			
			
			// Declare the array after loop has finished for use in view
			$this->table;
			
			if (!empty($this->table)) {
				
				return true;
				
			}
			
		}
		
		
		
		
		
		/**
		*
		*	Insert Info Into the Database
		*
		*	Used in Sub-Model's methods to insert into database
		*
		*	@access				protected			[used in sub-model's]
		*	@param				string				["$tableName", name of the table]
		*	@param				array				["$columns", columns to be inserted]
		*	^@param				array				["$values", values that are matched to columns]
		*	^@param				string				["$where", mysql parameters to be set]
		*	@return				boolean				[used in boolean checks]
		*
		*/
		protected function insert($tableName, $columns, $values = array(), $where = null) {
			
			$cols = "(`";
			$cols .= implode("`, `", $columns);
			$cols .= "`)";
			
			$table = "`" . $tableName . "`";
			
			$val = "('";
			$val .= implode("', '", $values);
			$val .= "')";
			
			
			$sql = "INSERT INTO " . $table . " " . $cols . " VALUES " . $val;
			
			
			// If It queries correctly, upload info and return true
			if ($this->conn->query($sql)) {
				
				return true;
				
			// There was an error; return false
			} else {
				
				echo "<b>Error I-01:</b> There was a problem inserting your data. Double Check Your Statement for any syntax errors.<br><br>";
				
				die ($this->conn->error);
				
				return false;
				
			}
			
		}
		
		
		
		/**
		*
		*	Update Info Into the Database
		*
		*	Used in Sub-Model's methods to insert into database
		*
		*	@access				protected			[used in sub-model's]
		*	@param				string				["$tableName", name of the table]
		*	@param				array				["$columns", columns to be inserted]
		*	@param				array				["$values", values that are matched to columns]
		*	^@param				string				["$where", mysql parameters to be set]
		*	@return				boolean				[used in boolean checks]
		*
		*/
		protected function update($tableName, $columns, $values, $where = null) {
			
			// Arrays need to be same size, otherwise return error
			if (count($columns) != count($values)) {
				
				return "Columns and values need to contain same number of elements";
				
			}
			
			// Loop through the arrays in this format ( `column` = 'value' )
			for ($i = 0; $i < count($columns); $i++) {
				
				$setPiece[] = "`" . $columns[$i] . "` = '" . $values[$i] . "'"; 
				
			}
			
			// Make pieces for upload into one string
			$set = implode(", ", $setPiece);
			
			// Format table name
			$table = "`" . $tableName . "`";
			
			// WHERE STATEMENT
			$where = "WHERE " . $where;
			
			// SQL string
			$sql = "UPDATE " . $table . " SET " . $set . " " . $where;
			
			// UN-COMMENT FOR DEBUGGING PURPOSES
			#echo $sql;
			
			// If query is successful, return true
			if ($this->conn->query($sql)) {
				
				return true;
			
			// Query not successful, return false
			} else {
				
				return false;
				
			}
			
		}
		
		
		
		// ALLOW QUERY OF RAW SELECT STATEMENTS
		public function rawSelect($statement, $tableName) {
			
			global $purifier;
			
			$sql = $statement;
			#echo $sql;
			
			$query = $this->conn->query($sql) or trigger_error($this->conn->error);
			
			
			// Store the value in a variable called table with an array of that table's name followed by it's values
			// EX: $model->table["bands"]["band_name"]
			//
			// Accessible by the individual page/directory's controller's
			
			while($row = $query->fetch_assoc()){
				
				// Store values as $model->table["tableName"]["columnName"]["index (usually 0)"]
				foreach ($row as $key => $val) {
					$this->data[$tableName][$key][] = $row[$key];
				}
				
			
			}
			
			
			// Loop through results to clean them
			// Foreach loops through each column
			// Make sure the table isn't empty (i.e. login returns an error)
			if (!empty($this->data[$tableName])) {
				foreach ($this->data[$tableName] as $key => $tableArray) {
					
					// For loop goes through each value in a certain row
					for ($i = 0; $i < count($tableArray); $i++) {
						// Convert from data variable to table after HTML PURIFIER
						$this->table[$tableName][$key][$i] = $purifier->purify($tableArray[$i]);
					}
					
				}
			}
			
			
			// Declare the array after loop has finished for use in view
			$this->table;
			
			if (!empty($this->table)) {
				return true;
			}
			
		}
		
		
		
		/**
		*
		*	Allow Join Table Select statements
		*
		*	Used in the sub-Model
		*
		*	@access		protected			[used in sub-model]
		*	@param			array					[$tables]
		*	@param			array					[$columns]
		*	^@param		string					[$where]
		*
		*/
		protected function joinSelect($tables, $columns, $where = null) {
			
			
			
		}
		
		
		
		
	}

?>
