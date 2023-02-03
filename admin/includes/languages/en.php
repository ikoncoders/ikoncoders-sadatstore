<?php

	function lang($phrase) {
		static $lang = array(
			// Navbar Links
			'HOME_ADMIN' => 'Home',
			'CATEGORIES' => 'Categories',
			'ITEMS' 	 => 'Items',
			'USERS' 	 => 'Users',
			'VENDORS' 	 => 'Vendors',
			'COMMENTS'	 => 'Comments',
			'STATISTICS' => 'Statistics',
			'LOGS' 		 => 'Logs',
			'MY_PROFILE' => 'My Profile',
			'NEW_AD'     => 'New Add',
			'MY_ADDS'    => 'My Adds',
			'LOGOUT'     => 'Logout',
			'' => ''
		);
		return $lang[$phrase];

	}
