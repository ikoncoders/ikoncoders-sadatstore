<?php

	/*
	================================================
	== Template Page
	================================================
	*/

	ob_start(); // Output Buffering Start

    include 'init.php';

	if (isset($_SESSION['USERNAME'])) {

        $pageTitle = 'Dashboard';

		

	

		



		include $tpl . 'footer.php';

	} else {

		header('Location: index.php');

		exit();
	}

	ob_end_flush(); // Release The Output

?>