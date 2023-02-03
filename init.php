<?php
session_start();
	// Error Reporting

	ini_set('display_errors', 'On');
	error_reporting(E_ALL);

	include 'admin/connect.php';

	$session = '';
	
	if (isset($_SESSION['USERNAME'])) {
		$session = $_SESSION['USERNAME'];
	}

	// Routes

	$tpl 	= 'includes/templates/'; // Template Directory
	$lang 	= 'includes/languages/'; // Language Directory
	$func	= 'includes/functions/'; // Functions Directory
	$css 	= 'layout/css/'; // Css Directory
	$js 	= 'layout/js/'; // Js Directory

	// Include The Important Files

	include $func . 'functions.php';
	include $lang . 'en.php';
	include $tpl . 'header.php';

    // Include Navbar On All Pages Expect The One With $noNavbar Vairable

	if (!isset($noNavbar)) { include $tpl . 'navbar.php'; }
	