<?php

	/*
	================================================
	== Template Page
	================================================
	*/

	ob_start(); // Output Buffering Start

	include 'init.php';

	$pageTitle = '';

	if (isset($_SESSION['aid'])) {

		

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if ($do == 'Manage') { // Manage Members Page

			$query = '';

			if (isset($_GET['page']) && $_GET['page'] == 'Pending') {

				$query = 'AND regStatus = 0';

			}

			// Select All Users Except Admin 

			$stmt = $con->prepare("SELECT * FROM users WHERE groupID != 1 $query ORDER BY id DESC");

			// Execute The Statement

			$stmt->execute();

			// Assign To Variable 

			$rows = $stmt->fetchAll();

			if (! empty($rows)) {

			?>

			<h1 class="text-center">Manage Members</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table manage-members text-center table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Avatar</td>
							<td>Username</td>
							<td>Email</td>
							<td>Full Name</td>
							<td>Registered Date</td>
							<td>Control</td>
						</tr>
						<?php
							foreach($rows as $row) {
								echo "<tr>";
									echo "<td>" . $row['id'] . "</td>";
									echo "<td>";
									if (empty($row['avatar'])) {
										echo 'No Image';
									} else {
										echo "<img src='uploads/avatars/" . $row['avatar'] . "' alt='' />";
									}
									echo "</td>";

									echo "<td>" . $row['username'] . "</td>";
									echo "<td>" . $row['email'] . "</td>";
									echo "<td>" . $row['fullName'] . "</td>";
									echo "<td>" . $row['created_at'] ."</td>";
									echo "<td>
										<a href='users.php?do=Edit&userid=" . $row['id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> </a>
										<a href='users.php?do=Delete&userid=" . $row['id'] . "' class='btn btn-danger confirm'><i class='fa fa-trash'></i>  </a>";
										if ($row['regStatus'] == 0) {
											echo "<a 
													href='users.php?do=Activate&userid=" . $row['id'] . "' 
													class='btn btn-warning activate'>
													<i class='fa fa-refresh'></i> </a>";
										}else{
                                            echo "<a 
													href='users.php?do=Deactivate&userid=" . $row['id'] . "' 
													class='btn btn-info activate'>
													<i class='fa fa-refresh'></i> </a>";
                                        }
									echo "</td>";
								echo "</tr>";
							}
						?>
						<tr>
					</table>
				</div>
				<a href="users.php?do=Add" class="btn btn-primary">
					<i class="fa fa-plus"></i> New User
				</a>
			</div>

			<?php } else {

				echo '<div class="container">';
					echo '<div class="nice-message">There\'s No Members To Show</div>';
					echo '<a href="members.php?do=Add" class="btn btn-primary">
							<i class="fa fa-plus"></i> New Member
						</a>';
				echo '</div>';

			} ?>

		<?php 

		} elseif ($do == 'Add') { // Add Page ?>

			<h1 class="text-center">Add New Member</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
					<!-- Start Username Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Username</label>
						<div class="col-sm-10 col-md-6">
							<input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="Username To Login Into Shop" />
						</div>
					</div>
					<!-- End Username Field -->
					<!-- Start Password Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Password</label>
						<div class="col-sm-10 col-md-6">
							<input type="password" name="password" class="password form-control" required="required" autocomplete="new-password" placeholder="Password Must Be Hard & Complex" />
							<i class="show-pass fa fa-eye fa-2x"></i>
						</div>
					</div>
					<!-- End Password Field -->
					<!-- Start Email Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10 col-md-6">
							<input type="email" name="email" class="form-control" required="required" placeholder="Email Must Be Valid" />
						</div>
					</div>
					<!-- End Email Field -->
					<!-- Start Full Name Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Full Name</label>
						<div class="col-sm-10 col-md-6">
							<input type="text" name="full" class="form-control" required="required" placeholder="Full Name Appear In Your Profile Page" />
						</div>
					</div>
					<!-- End Full Name Field -->
					<!-- Start Avatar Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">User Avatar</label>
						<div class="col-sm-10 col-md-6">
							<input type="file" name="avatar" class="form-control" required="required" />
						</div>
					</div>
					<!-- End Avatar Field -->
					<!-- Start Submit Field -->
					<div class="form-group form-group-lg">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" value="Add Member" class="btn btn-primary btn-lg" />
						</div>
					</div>
					<!-- End Submit Field -->
				</form>
			</div>

		<?php 

		} elseif ($do == 'Insert') {

			// Insert Member Page

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				echo "<h1 class='text-center'>Insert Member</h1>";
				echo "<div class='container'>";

				// Upload Variables

				$avatarName = $_FILES['avatar']['name'];
				$avatarSize = $_FILES['avatar']['size'];
				$avatarTmp	= $_FILES['avatar']['tmp_name'];
				$avatarType = $_FILES['avatar']['type'];

				// List Of Allowed File Typed To Upload

				$avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

				// Get Avatar Extension
                $path = $_FILES['avatar']['name'];

                $extension = pathinfo($path, PATHINFO_EXTENSION);

				// Get Variables From The Form

				$user 	= $_POST['username'];
				$pass 	= $_POST['password'];
				$email 	= $_POST['email'];
				$name 	= $_POST['full'];

				$hashPass = password_hash($pass, PASSWORD_DEFAULT);

				// Validate The Form

				$formErrors = array();

				if (strlen($user) < 4) {
					$formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
				}

				if (strlen($user) > 20) {
					$formErrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>';
				}

				if (empty($user)) {
					$formErrors[] = 'Username Cant Be <strong>Empty</strong>';
				}

				if (empty($pass)) {
					$formErrors[] = 'Password Cant Be <strong>Empty</strong>';
				}

				if (empty($name)) {
					$formErrors[] = 'Full Name Cant Be <strong>Empty</strong>';
				}

				if (empty($email)) {
					$formErrors[] = 'Email Cant Be <strong>Empty</strong>';
				}

				if (! empty($extension) && ! in_array($extension, $avatarAllowedExtension)) {
					$formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
				}
				if (empty($avatarName)) {
					$formErrors[] = 'Avatar Is <strong>Required</strong>';
				}
				if ($avatarSize > 4194304) {
					$formErrors[] = 'Avatar Cant Be Larger Than <strong>4MB</strong>';
				}
				// Loop Into Errors Array And Echo It
				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {

					$avatar = rand(0, 10000000000) . '.' . $extension;

					move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);

					// Check If User Exist in Database

					$check = check("Username", "users", $user);
					if ($check == 1) {
						$theMsg = '<div class="alert alert-danger">Sorry This User Is Exist</div>';
						redirect($theMsg, 'back');
					} else {
						// Insert Userinfo In Database

						$stmt = $con->prepare("INSERT INTO 
													users(username, password, email, fullName, regStatus, created_at, avatar)
												VALUES(:zuser, :zpass, :zmail, :zname, 0, now(), :zavatar) ");
						$stmt->execute(array(
							'zuser' 	=> $user,
							'zpass' 	=> $hashPass,
							'zmail' 	=> $email,
							'zname' 	=> $name,
							'zavatar'	=> $avatar
						));

						// Echo Success Message
						$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';

						redirect($theMsg, 'back');
					}
				}
			} else {
				echo "<div class='container'>";
				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
				redirect($theMsg);
				echo "</div>";
			}

			echo "</div>";

		} elseif ($do == 'Edit') {

			// Check If Get Request userid Is Numeric & Get Its Integer Value

			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

			// Select All Data Depend On This ID

			$stmt = $con->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");

			// Execute Query

			$stmt->execute(array($userid));

			// Fetch The Data

			$row = $stmt->fetch();

			// The Row Count

			$count = $stmt->rowCount();

			// If There's Such ID Show The Form

			if ($count > 0) { ?>

				<h1 class="text-center">Edit Member</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST"enctype="multipart/form-data">
						<input type="hidden" name="userid" value="<?php echo $userid ?>" />
						<!-- Start Username Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Username</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="username" class="form-control" value="<?php echo $row['username'] ?>" autocomplete="off" required="required" />
							</div>
						</div>
						<!-- End Username Field -->
						<!-- Start Password Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Password</label>
							<div class="col-sm-10 col-md-6">
								<input type="hidden" name="oldpassword" value="<?php echo $row['password'] ?>" />
								<input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Dont Want To Change" />
							</div>
						</div>
						<!-- End Password Field -->
						<!-- Start Email Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Email</label>
							<div class="col-sm-10 col-md-6">
								<input type="email" name="email" value="<?php echo $row['email'] ?>" class="form-control" required="required" />
							</div>
						</div>
						<!-- End Email Field -->
						<!-- Start Full Name Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Full Name</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="full" value="<?php echo $row['fullName'] ?>" class="form-control" required="required" />
							</div>
						</div>
						<!-- End Full Name Field -->
                        <!-- Start Avatar Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">User Avatar</label>
						<div class="col-sm-10 col-md-6">
                        <?php 
                            if (empty($row['avatar'])) {
                                echo 'No Image';
                            } else {
                                echo "<img src='uploads/avatars/" . $row['avatar'] . "' alt='' />";
                            }
                            ?>
							<input type="file" name="avatar" class="form-control" required="required" />
						</div>
					</div>
					<!-- End Avatar Field -->
						<!-- Start Submit Field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Save" class="btn btn-primary btn-lg" />
							</div>
						</div>
						<!-- End Submit Field -->
					</form>
				</div>

			<?php

			// If There's No Such ID Show Error Message

			} else {

				echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';

				redirect($theMsg);

				echo "</div>";

			}

		}elseif ($do == 'Update') { // Update Page

			echo "<h1 class='text-center'>Update User</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				// Get Variables From The Form

				$id 	= $_POST['userid'];
				$user 	= $_POST['username'];
				$email 	= $_POST['email'];
				$name 	= $_POST['full'];
                
                $avatarName = $_FILES['avatar']['name'];
				$avatarSize = $_FILES['avatar']['size'];
				$avatarTmp	= $_FILES['avatar']['tmp_name'];
				$avatarType = $_FILES['avatar']['type'];

				// List Of Allowed File Typed To Upload

				$avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

				// Get Avatar Extension
                $path = $_FILES['avatar']['name'];

                $extension = pathinfo($path, PATHINFO_EXTENSION);

                $formErrors = array();

                if (! empty($extension) && ! in_array($extension, $avatarAllowedExtension)) {
					$formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
				}
				if (empty($avatarName)) {
					$formErrors[] = 'Avatar Is <strong>Required</strong>';
				}
				if ($avatarSize > 4194304) {
					$formErrors[] = 'Avatar Cant Be Larger Than <strong>4MB</strong>';
				}

				// Password Trick

				$pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : password_hash($_POST['newpassword'], PASSWORD_DEFAULT);

				// Validate The Form

				

				if (strlen($user) < 4) {
					$formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
				}

				if (strlen($user) > 20) {
					$formErrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>';
				}

				if (empty($user)) {
					$formErrors[] = 'Username Cant Be <strong>Empty</strong>';
				}

				if (empty($name)) {
					$formErrors[] = 'Full Name Cant Be <strong>Empty</strong>';
				}

				if (empty($email)) {
					$formErrors[] = 'Email Cant Be <strong>Empty</strong>';
				}

				// Loop Into Errors Array And Echo It

				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {

                					$stmt2 = $con->prepare("SELECT 
												*
											FROM 
												users
											WHERE
												Username = ?
											AND 
												id != ?");

					$stmt2->execute(array($user, $id));

					$count = $stmt2->rowCount();

					if ($count) {

						$theMsg = '<div class="alert alert-danger">Sorry This User Is Exist</div>';

						redirect($theMsg, 'back');

					} else { 
                        $avatar = rand(0, 10000000000) . '.' . $extension;

                        move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);
						// Update The Database With This Info

						$stmt = $con->prepare("UPDATE users SET username = ?, email = ?, fullName = ?, password = ? , avatar =?, updated_at = now() WHERE id = ?");

						$stmt->execute(array($user, $email, $name, $pass, $avatar, $id));

						// Echo Success Message

						$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

						redirect($theMsg, 'back');

					}

				}

			} else {

				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

				redirect($theMsg);

			}

			echo "</div>";

		} elseif ($do == 'Delete') { // Delete Member Page

			echo "<h1 class='text-center'>Delete User</h1>";
			echo "<div class='container'>";

				// Check If Get Request userid Is Numeric & Get The Integer Value Of It

				$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

				// Select All Data Depend On This ID

				$check = check('id', 'users', $userid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("DELETE FROM users WHERE id = :zuser");

					$stmt->bindParam(":zuser", $userid);

					$stmt->execute();

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';

					redirect($theMsg, 'back');

				} else {

					$theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

					redirect($theMsg);

				}

			echo '</div>';

		} elseif ($do == 'Activate') {

			echo "<h1 class='text-center'>Activate Member</h1>";
			echo "<div class='container'>";

				// Check If Get Request userid Is Numeric & Get The Integer Value Of It

				$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

				// Select All Data Depend On This ID

				$check = check('id', 'users', $userid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("UPDATE users SET regStatus = 1 WHERE id = ?");

					$stmt->execute(array($userid));

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

					redirect($theMsg,'back');

				} else {

					$theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

					redirect($theMsg);

				}

			echo '</div>';

		}elseif ($do == 'Deactivate') {

			echo "<h1 class='text-center'>Deactivate Member</h1>";
			echo "<div class='container'>";

				// Check If Get Request userid Is Numeric & Get The Integer Value Of It

				$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

				// Select All Data Depend On This ID

				$check = check('id', 'users', $userid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("UPDATE users SET regStatus = 0 WHERE id = ?");

					$stmt->execute(array($userid));

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

					redirect($theMsg,'back');

				} else {

					$theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

					redirect($theMsg);

				}

			echo '</div>';

		}

		include $tpl . 'footer.php';

	} else {

		header('Location: index.php');

		exit();
	}

	ob_end_flush(); // Release The Output

?>