<?php

	class sessionData {
		
		public function __construct() {
			
			// IF USER ACCESSING ADMIN IS LOGGED IN AND OSTR ADMIN USER
			if (isset($_SESSION['access']) && $_SESSION['access'] == "admin") {
				
				$url = ADMIN_URI . $_SERVER['QUERY_STRING'];
				
				// IF USER IS ADMIN BUT NOT LOGGED INTO ADMIN PANEL,
				// AND USER IS NOT ON LOGIN PAGE
				// REDIRECT TO LOGIN PAGE
				if (!isset($_SESSION['adminUsername']) && $_SERVER['REQUEST_URI'] != "/login") {
					
					header('Location: ' . ADMIN_URI . 'login');
					exit;
					
				// Check if the Admin Session has expired
				// Boot user off if session is up. Send them back to login.
				} elseif ($this->timestampSess()) {
					
						unset($_SESSION['adminTimestamp']);
						unset($_SESSION['adminUsername']);
						unset($_SESSION['adminProfileImage']);
						
						header("Location: " . ADMIN_URI);
						 
				}
				
				
				
				// IF NEITHER, USER IS PASSED THROUGH
				
				
				
			// USER HAS NEITHER ACCESS AND IS REDIRECTED TO OSTR HOMEPAGE	
			} else {
				
				header('Location: ' . BASE_URI);
				exit;
				
			}
		
			
		}
		
		
		
		
		// CHECK IF CURRENT TIME IS PASSED TIMESTAMP
		private function timestampSess() {
			
			if (isset($_SESSION['adminTimestamp'])) {
			
				$time = time();
				
				
				// IF THE SESSION HAS EXPIRED
				if ($_SESSION['adminTimestamp'] < $time) {
					
					return true;
					
				
				// IF THE SESSION HASN'T EXPIRED AND PEOPLE ARE
				// STILL ACTIVE, RE-ISSUE THE SESSION
				} else {
					
					unset($_SESSION['adminTimestamp']);
					$_SESSION['adminTimestamp'] = time() + (60 * 60); #	1 HOUR
					
					return false;
					
				}
				
			} else {
			
				return false;
				
			}
			
		}
		
		
		
		
		/*
		static function sessionStart($name, $limit = 0, $path = '/', $domain = null, $secure = null) {
		 
			 // Set the cookie name before we start.
			 session_name($name . '_Session');

			 // Set the domain to default to the current domain.
			 $domain = isset($domain) ? $domain : isset($_SERVER['SERVER_NAME']);

			 // Set the default secure value to whether the site is being accessed with SSL
			 $https = isset($secure) ? $secure : isset($_SERVER['HTTPS']);

			 // Set the cookie settings and start the session
			 session_set_cookie_params($limit, $path, $domain, $secure, true);
			 session_start();
	    }
	    */
		
	}

?>
