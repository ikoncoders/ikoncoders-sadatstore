<?php
	ob_start();
	$noNavbar ='';
	
	$pageTitle = 'Login';
	if (isset($_SESSION['USERNAME'])) {
		header('Location: index.php');
	}
	include 'init.php';

	// Check If User Coming From HTTP Post Request

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		if (isset($_POST['login'])) {

			$user = $_POST['username'];
			$pass = $_POST['password'];
			

			// Check If The User Exist In Database

			$stmt = $con->prepare("SELECT 
										id, username, password 
									FROM 
										users 
									WHERE 
										username = ? 
									AND 
										regStatus = 1								
									LIMIT 1");

			$stmt->execute(array($user));

			$get = $stmt->fetch();

			$count = $stmt->rowCount();

			// If Count > 0 This Mean The Database Contain Record About This username

			if ($count) {
                if(password_verify($pass,$get['password'])){
                    
				$_SESSION['USERNAME'] = $user; // Register Session Name

				$_SESSION['uid'] = $get['id']; // Register User ID in Session

				header('Location: index.php'); // Redirect To Dashboard Page

				exit();
                }
			}

		} else {

			$formErrors = array();

			$username 	= $_POST['username'];
			$password 	= $_POST['password'];
			$password2 	= $_POST['password2'];
			$email 		= $_POST['email'];

			if (isset($username)) {

				$filterdUser = filter_var($username, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

				if (strlen($filterdUser) < 4) {

					$formErrors[] = 'username Must Be Larger Than 4 Characters';

				}

			}

			if (isset($password) && isset($password2)) {

				if (empty($password)) {

					$formErrors[] = 'Sorry Password Cant Be Empty';

				}

				if (sha1($password) !== sha1($password2)) {

					$formErrors[] = 'Sorry Password Is Not Match';

				}

			}

			if (isset($email)) {

				$filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

				if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true) {

					$formErrors[] = 'This Email Is Not Valid';

				}

			}

			// Check If There's No Error Proceed The User Add

			if (empty($formErrors)) {

				// Check If User Exist in Database

				$check = check("username", "users", $username);

				if ($check == 1) {

					$formErrors[] = 'Sorry This User Is Exists';

				} else {

					// Insert Userinfo In Database

					$stmt = $con->prepare("INSERT INTO 
											users(username, password, email, regStatus, created_at)
										VALUES(:zuser, :zpass, :zmail, 0, now())");
					$stmt->execute(array(

						'zuser' => $username,
						'zpass' => password_hash($password,PASSWORD_DEFAULT),
						'zmail' => $email

					));

					// Echo Success Message

					$succesMsg = 'Congrats You Are Now Registerd User';

				}

			}

		}

	}

?>

<div class="container login-page">
	<h1 class="text-center">
		<span class="selected" data-class="login">Login</span> | 
		<span data-class="signup">Signup</span>
	</h1>
	<!-- Start Login Form -->
	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<div class="input-container">
			<input 
				class="form-control" 
				type="text" 
				name="username" 
				autocomplete="off"
				placeholder="Type your username" 
				required />
		</div>
		<div class="input-container">
			<input 
				class="form-control" 
				type="password" 
				name="password" 
				autocomplete="new-password"
				placeholder="Type your password" 
				required />
		</div>
		<input class="btn btn-primary btn-block" name="login" type="submit" value="Login" />
	</form>
	<!-- End Login Form -->
	<!-- Start Signup Form -->
	<form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<div class="input-container">
			<input 
				pattern=".{4,}"
				title="username Must Be Between 4 Chars"
				class="form-control" 
				type="text" 
				name="username" 
				autocomplete="off"
				placeholder="Type your username" 
				required />
		</div>
		<div class="input-container">
			<input 
				minlength="4"
				class="form-control" 
				type="password" 
				name="password" 
				autocomplete="new-password"
				placeholder="Type a Complex password" 
				required />
		</div>
		<div class="input-container">
			<input 
				minlength="4"
				class="form-control" 
				type="password" 
				name="password2" 
				autocomplete="new-password"
				placeholder="Type a password again" 
				required />
		</div>
		<div class="input-container">
			<input 
				class="form-control" 
				type="email" 
				name="email" 
				placeholder="Type a Valid email" />
		</div>
		<input class="btn btn-success btn-block" name="signup" type="submit" value="Signup" />
	</form>
	<!-- End Signup Form -->
	<div class="the-errors text-center">
		<?php 

			if (!empty($formErrors)) {

				foreach ($formErrors as $error) {

					echo '<div class="msg error">' . $error . '</div>';

				}

			}

			if (isset($succesMsg)) {

				echo '<div class="msg success">' . $succesMsg . '</div>';

			}

		?>
	</div>
</div>

<?php 
	include $tpl . 'foot.php';
	
	ob_end_flush();
?>