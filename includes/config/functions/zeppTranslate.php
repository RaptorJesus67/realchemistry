<?php

	// Class to go back and forth between Zepp Code
	class zeppTranslate {
		
		// FUNCTIONS:
		//
		//		MAIN ---> zeppCode():
		//			- The function used to convert between zepp code,
		//				symbols, and strings.
		//
		//		Private ---> zeppArray():
		//			- For storing zepp codes, their symbols, and associated strings
		//
		//		!! Looping through every value may appear to be "slow", but the default "zepp" codes
		//		!! couldn't even be registered by the computer as even a microscopic fraction of a second.
		//
		//		!! Tested on:
		//				OS:	Windows 7
		//				RAM:	8GB
		//				CPU:	Intel i7, 2.5GHz, quad-core, 6MB cache
		
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		//
		// 			zeppArray()
		//
		//
		// DECLARES SPECIFIC ZEPP CODE FOR
		// EACH SYMBOL BEING USED FOR IT'S
		// WORD COUNTERPART
		//
		// Usage:
		// 		- This function is a private function that is used
		//			to determine symbols. All functions use the code
		//			adequately. Feel free to add your own.
		//			The syntax for each Zepp code:
		//
		//				array("@param1", "@param2", "@param3"),
		//				
		//			@param1:
		//				* Indicates the desired code. Begins with "%" and
		//					ends with "#". Between both characters is a made
		//					up three digit number that is used to decipher 
		//					meanings of Zepp code.
		//
		//			@param2:
		//				* Indicates the symbol to be used (i.e. "&")
		//
		//			@param3:
		//				* Indicates the @param2 English word. Like in @param2,
		//					the corresponding string would be "and".
		//
		//			@return:
		//				* Returns the entire array to be looped for appropriate values.
		
		private function zeppArray() {
			global $zeppArray;
			
			
			// All zepp code goes in this array based on the syntax above
			$zeppArray = array(
										array("%001#", "&", "and"),
										array("%002#", "@", "at"),
										array("%003#", "#", "hash"),
										array("%004#", "*", "asterisks"),
										array("%005#", "$", "dollar"),
										array("%006#", "%", "percent"),
										array("%007#", "^", "power"),
										array("%008#", "/", "slash"),
										array("%009#", "-", "dash"),
										array("%010#", "_", "underscore"),
										array("%011#", "+", "plus"),
										array("%012#", "=", "equals"),
										array("%013#", ".", "dot")
										);
			
			// Return entire array
			return $zeppArray;
		}
		
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//
		//			zeppCode()
		//
		//
		// CONVERT KEYBOARD SPECIAL CHARS INTO "ZEPP" CODE
		//
		// Usage:
		// 		- This function is designed to convert special chars or "symbols"
		//			into "zepp" code, a code stored in a database to be used to
		//			convert a character into either it's special char or it's English
		//			string. It's purpose is for a transition between proper usage and
		//			URL usage (i.e. "&", "%", "#" all have special meaning in URL's but
		//			are used by pop culture phrases and words).
		//
		//			@syntax:
		//				* The legal syntax usage for @param1 and @param2 are as following:
		//					
		//						- "symbol"
		//						- "zepp"
		//						- "string"
		//
		//				* Each possible parameter is self-explanatory and their usage is explained below.
		//				* If parameters are not legal syntax for function, an error is returned instead of desired string
		//				* Parameters can be used in any mixture that is desired (even if it @param1 and @param2 are the same).
		//
		//			@param1:
		//				* Indicates the starting point for the conversion (i.e. converting
		//					from symbol to zepp code).
		//
		//			@param2:
		//				* Indicates the ending point for the conversion (i.e. converting
		//					from zepp code to string).
		//
		//			@param3:
		//				* Indicates input string. It is inserted as a string with whitespace included
		//					(like a normal string of text).
		//
		//			@return:
		//				* Returns a string with whitespace exactly as entered, with the exception
		//					of the special chars being converted into zepp code/string or vice versa.
		//					(i.e. returns "Scruffy %001# the Janitors" instead of "Scruffy & the Janitors"), etc.
		//
		//					!!Main purpose is for database storage!!
		//					Use this to store in database for the purpose of making URL's legal, while maintaining
		//					the original text design through this simple and easy conversion.
		
		
		public function zeppCode($type1, $type2, $string) {
			
			$types = array($type1, $type2);
			$type = array();
			for($i = 0; $i < count($types); $i++) {
				
				switch($types[$i]) {
					
					case "symbol":
						$type[] = 1;
						break;
					
					case "zepp":
						$type[] = 0;
						break;
						
					case "string":
						$type[] = 2;
						break;
						
					default:
						return "Incorrect parameters used!";
						break;
					
				}
				
			}
			
			$stringArray = explode(" ", $string);
			$zeppArray = $this->zeppArray();
			$arrayVal = count($zeppArray);
			
			// Loop through each string
			$strings = array();
			foreach ($stringArray as $string) {
			
				// Check if this is a word on list
				for ($i = 0; $i < $arrayVal; $i++) {
					
					// If symbol is in list
					if ($string == $zeppArray[$i][$type[0]]) {
						
						// Return it's Zepp value
						$string = $zeppArray[$i][$type[1]];
					
					// Skip if it is not in the list
					} else {
						
						$string = $string;
						continue;
						
					}
					
				}
				$strings[] = $string;
			}
			
			$s = implode(" ", $strings);
			return $s;
			
		}
		
		
	}

?>
