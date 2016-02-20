<?php

	class urlInfo {
		
		public function trimURL() {
			
			$url = $_SERVER['REQUEST_URI'];
			
			
			$params = explode("/", $url);
			
			$url = array();
			for ($i = 0; $i < count($params); $i++) {
				
				# !IMPORTANT! Change this value to just 0 on actual server
				if ($i == 0) {
					# Skip those
					continue;
				}
				
				$url[$i - 1] = $params[$i];
				
			}
			
			return $url;
			
		}
		
	}

?>