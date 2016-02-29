<?php

	class elementView extends View {
		
		protected $table, $previous, $next;
		
		public function __construct($data = array()) {
			parent::__construct();
			
			$this->table = $data;
			$this->previous = "";
			$this->next = "";
			
			$this->getNeighborElements();
			
		}
		
		
		
		public function loadElementPage($element) {
			
			$title = ucfirst($element) . " | Real Chemistry";
			$template = "elementTemplate.html";
			
			$this->loadPage($title, $template);
			
		}
		
		
		
		protected function atomName() {
			
			
			return $this->table["elements"]["atom_name"][0];
		
		}
		
		
		
		private function getNeighborElements() {
			
			if (isset($this->table['elements'])) {
				
				$elem = $this->table['elements'];
				
				##########################
				# Get Current Element
				# '0' is the first element pulled, which is current
				$currentElement = $elem['atom_number'][0];
				
				for ($i = 1; $i < count($elem['atom_number']); $i++) {
					
					switch($elem['atom_number'][$i]) {
						
						# Gets the iteration for the next Element
						# Should be '2'
						case $currentElement + 1:
							$this->next = $i;
							break;
							
						# Gets the iteration for the previous Element
						# Should be '1'
						case $currentElement - 1:
							$this->previous = $i;
							break;
							
						default:
							continue;
							break;
						
					} # END switch()
					
				} # END for()
				
			} # END isset()
			
		} # END getNeighborElements()
		
		
		
		
		protected function previousText() {
			
			# ERROR-HANDLING
			# If Element Doesn't Exist
			if (!isset($this->table['elements']['atom_name'][$this->previous])) {
					
				$this->table['elements']['atom_name'][$this->previous] = "";
				
			} 
			
			return $this->table['elements']['atom_name'][$this->previous];
			
		}
		
		
		protected function previousLink() {
			
			if (!empty($this->previousText())) {
				
				$link  = "&larr; ";
				$link .= "<a href='" . BASE_URI . "element/" . strtolower($this->previousText()) . "'>";
				$link .=	$this->previousText();
				$link .= "</a>";
				
				return $link;
				
			} else {
			
				return "";
				
			}
			
		}
		
		
		
		
		protected function nextText() {
			
			# ERROR-HANDLING
			# If Element Doesn't Exist
			if (!isset($this->table['elements']['atom_name'][$this->next])) {
					
				$this->table['elements']['atom_name'][$this->next] = "";
				
			} 
			
			return $this->table['elements']['atom_name'][$this->next];
			
		}
		
		
		protected function nextLink() {
			
			if (!empty($this->nextText())) {
				
				$link  = "<a href='" . BASE_URI . "element/" . strtolower($this->nextText()) . "'>";
				$link .=	 $this->nextText();
				$link .= "</a>";
				$link .= " &rarr;";
				
				return $link;
				
			} else {
			
				return "";
				
			}
			
		}
		
		
		
		
	}

?>
