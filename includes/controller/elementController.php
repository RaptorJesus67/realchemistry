<?php
	
	
	// No Need to Extend Main Controller
	// Sub-Controller already called in Main
	class elementController {
		
		public $e, $li;
		
		public function __construct() {
			
			
			global $url;
			
			$this->model = new elementModel;
			$this->view = new elementView;
			
			$this->url = $url->trimURL();
			
			
			if (isset($_POST['data'])) {
				
				$jesus = json_decode($_POST['data'], true);
				//$input = json_decode($_POST['input'], true);
				
				// Get List of Elements
				$el = $this->elements($jesus);
				
				// Sort Through Elements
				$this->sortBySearch($_POST['input']);
				
				echo json_encode($this->li);
			
			} elseif (isset($_POST['initial'])) {
				
				$jesus = json_decode($_POST['initial'], true);
				$el = $this->elements($jesus);
				
				echo json_encode($this->e);
			
			
			// Actual Page
			} else {
				
				if (isset($this->url[0])) {
					
					$this->singleElementPage();
					
					
				} else {
					
					echo "NO";
				
				}
				
			}
			
		}
		
		
		
		private function elements($data) {
			
			$this->e = array();
			
			// Table and "Actinoids/Lathanoids" re seperately set
			$table = $data['table'];
			$bottom = array($data['actinoids'], $data['lanthanoids']);
			
			$i = 0;
			// Loop Through Table to get values
			foreach ($table as $row) {
			
				foreach ($row as $set) {
					
					
					if (is_array($set) || is_object($set)) {
				
						foreach ($set as $element) {
							
							$this->e[$i]['name'] = $element['name'];
							$this->e[$i]['mass'] = @$element['molar'];
							$this->e[$i]['num'] = $element['number'];
							$this->e[$i]['symbol'] = $element['small'];
							$this->e[$i]['group'] = $this->elementGroup($element['group']);
							
							$i++;
							
						}
					
					}
					
				}
				
			}
			
			
			foreach ($bottom as $type) {
				
				foreach ($type as $element) {
					
					$this->e[$i]['name'] = $element['name'];
					$this->e[$i]['mass'] = @$element['molar'];
					$this->e[$i]['num'] = $element['number'];
					$this->e[$i]['symbol'] = $element['small'];
					$this->e[$i]['group'] = $this->elementGroup($element['group']);
					
					$i++;
				}
				
			}
			
			
		}
		
		
		
		
		private function elementGroup($group) {
		
		
			switch($group) {
					
				case strpos($group, "Special") !== false:
					return "None";
					break;	
					
				case strpos($group, "Actinoid") !== false:
					return "Actinide";
					break;
					
				case strpos($group, "Lanthanoid") !== false:
					return "Lanthanide";
					break;
					
				case strpos($group, "Alkaline") !== false:
					return "Alkaline Earth";
					break;
					
				case strpos($group, "Alkali") !== false:
					return "Alkali";
					break;
					
				case strpos($group, "Noble") !== false:
					return "Noble Gas";
					break;
					
				case strpos($group, "Nonmetal") !== false:
					return "Non-Metal";
					break;
					
				case strpos($group, "Metalloid") !== false:
					return "Semi-Metal";
					break;
				
				case strpos($group, "Halogen") !== false:
					return "Halogen";
					break;
					
				case strpos($group, "Transition") !== false:
					return "Transition Metal";
					break;	
				
				case strpos($group, "Poor") !== false:
					return "Basic Metal";
					break;
				
				default:
					return "None";
					break;
				
			}
			
		}
		
		
		private function sortBySearch($term) {
		
			for ($i = 0; $i < count($this->e); $i++) {
				
				$l = strtolower($term);
				$u = strtoupper($term);
				
				// If Term matches
				if (strpos($this->e[$i]["name"], $term) !== false ||
					strpos($this->e[$i]["name"], $u) !== false ||
					strpos($this->e[$i]["name"], $l) !== false) {
				
					$this->li = $this->e;
					
				}
				
			}
			
		}
		
		
		
		private function singleElementPage() {
			
			
			$this->model->loadElementData($this->url[1]);
			
			// Last and Next Elements
			$this->model->loadPreviousElement($this->model->table["elements"]["atom_number"][0]);
			$this->model->loadNextElement($this->model->table["elements"]["atom_number"][0]);
			
			# Initiate View after All DB Calls
			$this->view = new elementView($this->model->table);
			$this->view->loadElementPage($this->url[1]);
			
			
			
		}
		
		
		
	}

?>
