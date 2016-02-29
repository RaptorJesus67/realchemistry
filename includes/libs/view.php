<?php

	class View {
		
		public $head, $foot;
		
		public function __construct() {
			
			$this->pageName = "Home";
			
		}
		
		
		
		protected function setTitle($title = "Real Chemistry") {
			
			return $title;
			
		}
		
		
		
		protected function loadPage($title = "Real Chemistry", $templateName = "homeTemplate.php") {
			
			$this->title = $title;
			
			require_once(BASE_INCLUDE . "header.php");
			require_once(BASE_INCLUDE . "templates/" . $templateName);
			require_once(BASE_INCLUDE . "footer.php");
			
		}
		
		
		
		
		/**
		*
		*	Convert standard numDate-Stamp to PHP Date
		*
		*	Used in Sub-View methods
		*
		*	@access			protected			[for use in sub-view]
		*	@param			string				["INPUT", the numDate-Stamp to change]
		*	^@param			string				["REGEX", Regex for PHP `date()`;
		*											- Default "M j, Y"; (i.e. Jan 1, 1970)]
		*	@return			
		*
		*/
		protected function convertNumToYear($input, $regex = "M j, Y") {
			
			$count = strlen($input);
			
			// Ensure it is number only in the following format: '01011970'
			if (ctype_digit($input)) {
				
				// If string is MMDDYYYY, MMYYYY, or YYYY only
				if ($count == 8 || $count == 6 || $count == 4) {
					
					// Break string differently based on count
					switch ($count) {
					
						case $count == 8:
							$s[] = substr($input, 0, 2);
							$s[] = substr($input, 2, 2);
							$s[] = substr($input, 4, 4);
							
							// "MM/DD/YYYY"
							$stamp = implode ("/", $s);
							
							$time = strtotime($stamp);
							return date($regex, $time);
							break;
							
						case $count == 6:
							$s[] = substr($input, 0, 2);
							$s[] = substr($input, 2, 4);
							
							// "MM/YYYY"
							$stamp = implode(" ", $s);
							
							if (strtotime($stamp) == null) {
							
								return $this->classErrors("V-02");
								
							}
							
							return date($regex, strtotime($stamp));
							break;
							
						case $count == 4:
						
							$stamp = $input;
						
							return date($regex, strtotime($stamp));
							break;
							
						default:
							return $this->classErrors("V-01");
							break;
						
					}
				
				// Wrong string type, return nothing;	
				} else {
					
					return null;
					
				}
				
			// String not digit only
			} else {
				
				return null;
			
			}
			
			
		}
		
		
		
		
		/**
		*
		*	Take a pure number, make it a string with commas in proper places
		*
		*	Used for big numbers in sub-view
		*
		*	@access			protected			[used in sub-view]
		*	@param			int					["Input", an iteger. Does not work on float or double]
		*	@return			string				[Add commas and return as string]
		*
		*/
		protected function addNumberCommas($input) {
			
			// Get count of characters for "for" loop
			$count = strlen($input);
			
			if (!ctype_digit($input)) {
			
				return $this->classErrors("V-03");
				
			}
			
			// Initiate aray and variable
			$j = array();
			$number = "";
			
			// Loop Through each character
			for ($i = 0; $i < $count; $i++) {
				
				// Actually seperates each character and temp's it to array
				$j[$i] = substr($input, $i, 1);
				
				// If the count is on third modulo iteration and not first, add comma
				if (($count - $i) % 3 == 0 && $i != 0) {
					
					// Add comma to string
					$number .= ",";
					
				}
				
				// Add each number to string
				$number .= $j[$i];
				
				
			}
			
			// return string
			return $number;
			
		}
		
		
		
		protected function profileInfoWidget($bootstrap = "standard", $info = array(), $image = null, $angular = array()) {
			
			
			// BOOTSTRAP SETTINGS
			switch ($bootstrap) {
			
				case "standard":
					
					$css["wrapper"] = "userInfoContainer";
					$css["infoWrapper"] = "userProfileBasicInfo";
					$css["imageWrapper"] = "userImageWrapper";
					$css["userImage"] = "userProfilePicture";
					$css["profileShow"] = "userProfileInfoTag";
					$css["infoKey"] = "userInfoLeft";
					$css["infoVal"] = "userInfoRight";
					$css["infoRow"] = "userInfoRow";
					
					// Standard bootstrap
					break;
					
				
				// Default
				default:
					
					break;
				
			}
			
			
			if (!empty($angular)) {
				
				
			
			}
			
			
			// Start HTML <div>
			$html = "<div class='" . $css["wrapper"] . "'>";
			
			
			// IF IMAGE EXISTS
			if(!empty($image)) {
				
				$html .= "<div class='" . $css["imageWrapper"] . "'>";
				$html .= "<img src='" . BASE_URI . "images/users/" . $image . "' class='" . $css["userImage"] . "' />";
				$html .= "</div>";
				
			}
			
			if ($bootstrap == "standard") {
				
				$html .= "<span id='" . $css["profileShow"] . "'>";
				$html .= "<p>Profile Info</p>";
				$html .= "</span>";
				
			}
			
			$html .= "<div class='" . $css["infoWrapper"] . "'>";
			
			// LOOP THROUGH INFO
			foreach ($info as $key => $value) {
				
				$html .= "<div class='" . $css["infoRow"] . "'>";
				$html .= "<div class='" . $css["infoKey"] . "'>" . $key . "</div>";
				$html .= "<div class='" . $css["infoVal"] . "'>" . $value . "</div>";
				$html .= "</div>";
				
			}
			
			$html .= "</div>";
			
			return $html;
			
		}
		
		
		
		protected function createButton($name, $style = "standard", $href = BASE_URI) {
			
			// SWITCH FOR CSS STYLES
			switch($style) {
				
				// Standard Styles
				case "standard":
					$css["button"] = "standardButtonStyle";
					$css["buttonDiv"] = "standardButtonDivStyle";
					break;
					
					
				default:
					$css["button"] = "standardButtonStyle";
					$css["buttonDiv"] = "standardButtonDivStyle";
					break;
				
			}
			
			
			// CREATE BUTTON
			$html = "<div class='" . $css["buttonDiv"] . "'>";
				$html .= "<a href='" . $href . "'>";
					$html .= "<button class='" . $css["button"] . "'>";
						$html .= $name;
					$html .= "</button>";
				$html .= "</a>";
			$html .= "</div>";
			
			return $html;
			
		}
		
		
		
		
		
		
		protected function createForm($action = "#", $method = "get", $attr = array()) {
			
			// START FORM OPENING TAG
			$form = "<form action='" . $action . "' method='" . $method . "' ";
			
			// EXTRA ATTRIBUTES
			if (!empty($attr)) {
				
				$atts = "";
				foreach ($attr as $attribute => $value) {
					
					$atts .= $attribute . "='" . $value . "' ";
					
				}
				
				$form .= $atts;
				
			}
			
			// END FORM OPENING TAG
			$form .= ">";
			
			return $form;
			
		}
		
		
		
		protected function createInput($array) {
			
			// SET UP INPUT
			$input = "<input ";
			
			foreach($array as $attr => $value) {
				
				// IF ATTRIBUTE ONLY
				if ($value == "") {
					
					$input .= $attr . " ";
					
				// IF ATTRIBUTE HAS VALUE
				} else {
					
					$input .= $attr . "='" . $value ."'";
					
				}
				
			} // END FOREACH
			
			// END INPUT TAG
			$input .= "/>";
			
			// RETURN INPUT
			return $input;
			
		}
		
		
		
		protected function createSelect($array) {
			
			
			$html = "<select ";
			
			foreach($array as $attr => $value) {
				
				if ($attr == "values") {
					
					$html .= ">";
					
					foreach($value as $att => $val) {
						
						$html .= "<option value='" . $val . "'>" . $val . "</option>";
						
					}
				
				} else {
				
					$html .= $attr . "='" . $value . "'";
					
				}
				
			}
			
			
			$html .= "</select>";
			
			return $html;
			
		}
		
		
		
		
		protected function endForm() {
		
			$end = "</form>";
			return $end;
			
		}
		
		
		
		protected function stateSelectList() {
		
			$states = array(
							''	=>'--',
							'AL'=>'AL',
							'AK'=>'AK',
							'AS'=>'AS',
							'AZ'=>'AZ',
							'AR'=>'AR',
							'CA'=>'CA',
							'CO'=>'CO',
							'CT'=>'CT',
							'DE'=>'DE',
							'DC'=>'DC',
							'FM'=>'FM',
							'FL'=>'FL',
							'GA'=>'GA',
							'GU'=>'GU',
							'HI'=>'HI',
							'ID'=>'ID',
							'IL'=>'IL',
							'IN'=>'IN',
							'IA'=>'IA',
							'KS'=>'KS',
							'KY'=>'KY',
							'LA'=>'LA',
							'ME'=>'ME',
							'MH'=>'MH',
							'MD'=>'MD',
							'MA'=>'MA',
							'MI'=>'MI',
							'MN'=>'MN',
							'MS'=>'MS',
							'MO'=>'MO',
							'MT'=>'MT',
							'NE'=>'NE',
							'NV'=>'NV',
							'NH'=>'NH',
							'NJ'=>'NJ',
							'NM'=>'NM',
							'NY'=>'NY',
							'NC'=>'NC',
							'ND'=>'ND',
							'MP'=>'MP',
							'OH'=>'OH',
							'OK'=>'OK',
							'OR'=>'OR',
							'PW'=>'PW',
							'PA'=>'PA',
							'PR'=>'PR',
							'RI'=>'RI',
							'SC'=>'SC',
							'SD'=>'SD',
							'TN'=>'TN',
							'TX'=>'TX',
							'UT'=>'UT',
							'VT'=>'VT',
							'VI'=>'VI',
							'VA'=>'VA',
							'WA'=>'WA',
							'WV'=>'WV',
							'WI'=>'WI',
							'WY'=>'WY',
							'AE'=>'AE',
							'AA'=>'AA',
							'AP'=>'AP'
						);
						
						
			return $states;
			
		}
		
		
		
		
			protected function countrySelectList() {
		
		$countries = array("--", "United States", "Canada", "United Kingdom", 
		"Afghanistan", "Albania", "Algeria", "American Samoa", 
		"Andorra", "Angola", "Anguilla", "Antarctica", 
		"Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", 
		"Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", 
		"Belize", "Benin", "Bermuda", "Bhutan", "Bolivia",
		"Botswana", "Brazil",
		"Bulgaria", "Burkina Faso", "Burundi", "Cambodia", 
		"Cameroon", "Canada", "Cape Verde", "Cayman Islands", 
		"Chad", "Chile", "China",
		"Colombia", "Comoros", "Congo", 
		"Cook Islands", "Costa Rica", 
		"Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", 
		"Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", 
		"Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", 
		"Ethiopia", "Falkland Islands", "Faroe Islands", "Fiji", 
		"Finland", "France", "French Guiana", 
		"Gabon", "Gambia", 
		"Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", 
		"Grenada", "Guadeloupe", "Guatemala", "Guinea", "Guinea-Bissau", 
		"Guyana", "Haiti", "Honduras", "Hong Kong", 
		"Hungary", "Iceland", "India", "Indonesia", "Iran", 
		"Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", 
		"Kazakhstan", "Kenya", "Kiribati",
		"Kuwait", "Kyrgyzstan", 
		"Latvia", "Lebanon", "Lesotho", 
		"Liberia", "Libya", "Liechtenstein", "Lithuania", 
		"Luxembourg", "Macau", "Macedonia", 
		"Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", 
		"Martinique", "Mauritania", "Mauritius", "Mayotte", 
		"Mexico", "Micronesia", "Moldova", 
		"Monaco", "Mongolia", "Montserrat", "Mordor", "Morocco", "Mozambique", "Myanmar (Burma)", 
		"Namibia", "Nauru", "Nepal", "Netherlands", 
		"New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", 
		"Norway", "Oman", "Pakistan", 
		"Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", 
		"Poland", "Portugal", "Puerto Rico", "Qatar",
		"Romania", "Russia", "Rwanda", 
		"Saint Lucia", "Samoa", "San Marino", 
		"Saudi Arabia", "Senegal", "Seychelles", 
		"Sierra Leone", "Singapore", "Slovenia", 
		"Somalia", "South Africa", "South Korea", "Spain", "Sri Lanka", 
		"St. Helena", "Sudan", "Suriname", 
		"Swaziland", "Sweden", "Switzerland", 
		"Syria", "Taiwan", "Tajikistan", 
		"Tanzania", "Thailand", "Tunisia", "Turkey", 
		"Turkmenistan", 
		"Uganda", "Ukraine", 
		"United Arab Emirates", "Uruguay", "Uzbekistan", 
		"Vanuatu", "Venezuela", "Vietnam",
		"Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
						
						
			return $countries;
			
		}
		
		
		
		
		
		
		private function classErrors($error) {
			
			switch($error) {
				
				case "V-01":
					return "<strong>ERROR V-01:</strong> A numeric, non-acceptance string was passed in convertNumToYear().
							Please check your code and reload page.";
					break;
					
				case "V-02":
					return "<strong>ERROR V-02:</strong> There was a problem with the numStamp input in convertNumToYear().
							Ensure your string code is MMDDYYYY or YYYY";
					break;
					
				case "V-03":
					return "<strong>ERROR V-03:</strong> There was a problem with the input in addNumberCommas().
							Only integers are allowed in the function. No decimals can be used currently";
				
				default:
					return "<strong>ERROR V-00:</strong> Illegal error-code presented to error reporter";
					break;
				
			}
			
		}
		
	}

?>
