<?php

	require_once("../includes/config/config.php");
	
	require_once(BASEPATH . "includes/libs/controller.php");
	require_once(BASEPATH . "includes/libs/model.php");
	require_once(BASEPATH . "includes/libs/view.php");

	$controller = new Controller($url->trimURL());
?>
