<?php

	// Sub-controller for the home page
	class homeController {
		
		// Model info to be used in controller
		private $model;
		
		// View info to be used in controller
		private $view;
		
		
		/**
		 * 
		 * 
		 * 
		 */
		public function __construct() {
			// While on the sub-model and sub-view, you need to construct the parent,
			// the sub-controller is already called within the main controller
			
			
			/////	 GLOBALS 	////
			
			# This is the class that maintains the URL structure.
			# Use it to load certain pieces/pages based on user input
			global $url;
			
			
			# Makes the URL class a local variable
			$this->url = $url;
			
			$this->view = new homeView;
			
		
		}
	
	}

?>
