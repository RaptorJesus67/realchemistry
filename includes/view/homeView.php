<?php

	class homeView extends View {
		
		public function __construct() {
			parent::__construct();
			
			
			$this->loadHomePage();
			
			
		}
		
		
		
		private function loadHomePage() {
			
			$title = "Real Chemistry";
			$template = "homeTemplate.html";
			
			$this->loadPage($title, $template);	
			
		}
		
	}

?>
