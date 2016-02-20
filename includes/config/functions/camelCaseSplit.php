<?php

	class camelCaseSplit {
		
		public function camelCase($type, $string) {
			
			switch($type) {
				
				case "create":
					$nine = 1;
					break;
				
				case "break":
					$nine = 2;
					break;
					
				default:
					$nine = "Illegal syntax for Parameter 1";
					break;
				
			}
			
			// Declare camel as null string so it can be appended later
			$camel = null;
			
			if ($nine == 1) {
				
				// Lower all characters in the string as a base
				$string = strtolower($string);
				
				// Separate each space in the string to give each word an array iteration
				$stringArray = explode(" ", $string);
				
				
				// Loop through each iteration (word)
				for ($i = 0; $i < count($stringArray); $i++) {
					
					// If first iteration, make it lowercase by skipping it
					if ($i == 0) {
						
						$stringArray[$i];
						
					// Every other iteration gets first character uppercase(d)
					} else {
						
						$stringArray[$i] = ucfirst($stringArray[$i]);
						
					}
					
					// Append each value to the string 
					$camel .= $stringArray[$i];
					
				}
				
				// Return the string to the user in camel case form
				return $camel;
				
				
			} elseif ($nine == 2) {
				
				// A BUG. Sometimes string is entered as an array
				if (is_array($string)) {
					
					$string = $string[1];
					
				}
				
				$camel = preg_split('/(?=[A-Z&.])/', $string);
	
				for ($i = 0; $i < count($camel); $i++) {
					$camel[$i] = strtolower($camel[$i]);
				}
				$cam = implode(" ", $camel);
				return $cam;
			} else {
				return $nine;
			}
			
		}
		
	}

?>