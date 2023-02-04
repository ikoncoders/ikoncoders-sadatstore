<?php
	
	$noAdminNavbar = '';
	$pageTitle = 'Login';

    include 'init.php';

	if (isset($_SESSION['aid'])) {
		header('Location: admin_dashboard.php'); // Redirect To Dashboard Page
	}

	// Check If User Coming From HTTP Post Request

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {		

        //declare login variable
			$username        = $_POST['username'];
			$password        = $_POST['password'];			

			// Check If The User Exist In Database
        
			$stmt = $con->prepare("SELECT 
										id, username, password, groupID, regStatus
									FROM 
										users 
									WHERE 
										username = ?
                                    AND 
                                        groupID = 1
                                    AND 
                                        regStatus = 1
                                    LIMIT 1
									");

            $stmt->execute([$username]);

            $userData = $stmt->fetch();
            $count = $stmt->rowCount();              

			// If Count > 0 This Mean The Database Contain Record About This Username

			if ($count) {    
                // Verify the entered password against the hashed password             
                 if (password_verify($password, $userData['password'])) { 
                    $_SESSION['ADMIN'] = $username; // Register the username in the session
 					$_SESSION['aid'] = $userData['id']; // Register the user ID in the session
                    $succesMsg = 'You have login successfully.'; // login successful
                    header('Location: admin_dashboard.php'); // Redirect to the dashboard page
                    exit();                   
                } else{
                    $errorMessage = 'Invalid Credentials';                   
                } 				
                } else {
                    $errorMessage = 'Invalid credentials';
            }
		}
   
?>

	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<h4 class="text-center">Admin Login</h4>
		<input class="form-control" type="text" name="username" placeholder="Username" autocomplete="off" />
		<input class="form-control" type="password" name="password" placeholder="Password" autocomplete="new-password" />
		<input class="btn btn-primary btn-block" type="submit" value="Login" />
	</form>

<?php include $tpl . 'footer.php'; ?>