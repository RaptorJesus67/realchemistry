<?php
	
	
	// Start Session
	ini_set('session.cookie_domain', ".realchemistry.loc"); // Change to ".yourwebserver.domaintype"
	session_start();
	
	
	
	error_reporting(E_ALL); // Change to "E_NONE" on the real server
	ini_set( "display_errors", 1 ); // Change to "0" on the real server
	
	define('BASE_DOMAIN', 'realchemistry.loc');
	define('BASE_INCLUDE', '/home/realchem/includes/');
	define('BASE_URI', 'http://realchemistry.loc/');
	define('ROOT', '/realchem/');
	define('BASEPATH', "/home/realchem/");
	define('BASE_CLIENT', BASEPATH . "public_html/");
	
	
	# USED FOR AJAX REQUESTS
	#
	#	example: 
	#
	#		if (IS_AJAX) {
	#		
	#			// Ajax request to controller
	#
	#		}
	#
	#
	define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	
	
	###########################################
	#	A GLOBAL SITE SALT
	#
	#	Design your own site salt to be hashed with desired content.
	#
	#	DO NOT USE A GLOBAL SALT AND SHA1/MD5 FOR USER PASSWORDS!!!!!
	#
	#	A global salt should be used for site related, somewhat-sensitive data.
	#	
	#	For user passwords use the:
	#		$clean->sha256($userPassword, "yes");
	#	This will generate a random hash and password in an array to be stored
	#	into the database. See "includes/config/functions/cleanInput.php" for more insight.
	define('SALT', '');
	
	require_once("functions/sessionData.php");
	#$sess = new sessionData;
	
	// FUNCTIONS LOADING
	require_once(BASEPATH . "includes/config/functions/functions.php");
	
	//	DATABASE LOADING
	require_once(BASEPATH . "includes/config/db/database.php");
	
	
	###########################################
	# CALL ANY GENERAL FUNCTION CLASSES HERE
	#
	#
	#	CLEAN FUNCTIONS FOR: 
	#
	#		- Sanitizing (Basic; use HTMLPurifier for advanced sanitizing)
	#		- Sha256 Hashing (more secure to use with MySQL Passwords)
	#		- Random Strings (Use to generate random strings like SALTS for user passwords)
	#
	 $clean = new cleanInput;
	#
	#
	#	URL TRIMMING TO DECIPHER CONTROLLER REQUESTS
	 $url = new urlInfo;
	#
	#
	#	A CODE THAT IS STORED IN DATABASE TO GENERATE PRETTY URL'S
	#
	#	The concept is to allow word only url's without removing sensitive characters
	#	(i.e. &, %, #, @, $, *, =, -, _, etc)
	 $zepp = new zeppTranslate;
	#
	#
	#	CAMEL CASING THE URL'S
	#
	#	Step two of pretty URL's. Allows for spaces between words by using
	#	common programming design of "camel case"
	 $camel = new camelCaseSplit;
	#
	#
	# HTML PURIFIER INSTANCE
	#
	#	DO NOT ADJUST UNLESS YOU ARE FAMILIAR WITH HTMLPURIFIER SETTINGS!!!
	#
	 $purifyConfig = HTMLPurifier_Config::createDefault();
     $purifier = new HTMLPurifier($purifyConfig);
    #
    #
    ###########################################
	
	
	
	
?>
