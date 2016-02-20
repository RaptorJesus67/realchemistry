<?php

	class cleanInput {
		
		
		// Sanitize Input
		function sanitize($input) {
			global $conn;
			$search = array(
				'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
				'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
				'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
				'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
			);

			$preg = preg_replace($search, '', $input);
			$output = mysqli_real_escape_string($conn, $preg);
			
			return $output;
		}
		
		
		
		// Random String
		function randString($length = 32) {
			$characters = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
			$characterLen = strlen($characters);
			$randomString = "";
			for ($i = 0; $i < $length; $i++){
				$randomString .= $characters[rand(0, $characterLen - 1)];
			}
			return $randomString;
		}
		
		
		// PRINT_R IN STYLE!
		function arrayView($input) {
			echo "<pre>";
			print_r($input);
			echo "</pre>";
		}
		
		
		
		public function sha256($input, $rand = "no", $hash = null) {
			
			$info["salt"] = ($hash != null) ? $hash : $this->randString();
			$info["hash"] = hash("sha256", $info["salt"] . $input);
			
			return $info;
				
		}
		
		
	}

?>
