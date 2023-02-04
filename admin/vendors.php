<?php

	/*
	================================================
	== Template Page
	================================================
	*/

	ob_start(); // Output Buffering Start

    $pageTitle = 'Vendor';

    include 'init.php';

	if (isset($_SESSION['aid'])) {
		

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') { // Manage Members Page

			// $query = '';
			// if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
			// 	$query = 'AND regStatus = 0';
			// }

			// Select All Users Except Admin 

			//$stmt = $con->prepare("SELECT * FROM users WHERE groupID = 2 $query ORDER BY id DESC");
			// Execute The Statement
			//$stmt->execute();

			// Assign To Variable 

			//$rows = $stmt->fetchAll();

			$stmt = $con->prepare("SELECT
					users.id AS user_id,
					users.name,
					vendors.id AS comp_id,
					vendors.name
				FROM
					users
				LEFT JOIN
					vendors
				ON
			users.id = vendors.user_id ");

			if (! empty($rows)) {

			?>

			<h1 class="text-center">Manage Vendors</h1>
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
									if (empty($row['logo'])) {
										echo 'No Image';
									} else {
										echo "<img src='uploads/vendors/" . $row['logo'] . "' alt='' />";
									}
									echo "</td>";

									echo "<td>" . $row['username'] . "</td>";
									echo "<td>" . $row['email'] . "</td>";
									echo "<td>" . $row['fullName'] . "</td>";
									echo "<td>" . $row['created_at'] ."</td>";
									echo "<td>
										<a href='vendors.php?do=Edit&userid=" . $row['id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
										<a href='vendors.php?do=Delete&userid=" . $row['id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
										if ($row['RegStatus'] == 0) {
											echo "<a 
													href='vendors.php?do=Activate&userid=" . $row['id'] . "' 
													class='btn btn-info activate'>
													<i class='fa fa-check'></i> Activate</a>";
										}
									echo "</td>";
								echo "</tr>";
							}
						?>
						<tr>
					</table>
				</div>
				<a href="vendors.php?do=Add" class="btn btn-primary">
					<i class="fa fa-plus"></i> New Vendor
				</a>
			</div>

			<?php } else {

				echo '<div class="container">';
					echo '<div class="nice-message">There\'s No Vendors To Show</div>';
					echo '<a href="vendors.php?do=Add" class="btn btn-primary">
							<i class="fa fa-plus"></i> New Vendor
						</a>';
				echo '</div>';

			} ?>

		<?php 

		} elseif ($do == 'Add') { ?>

			<h1 class="text-center">Add New Vendor</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
					<!-- Start Name Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10 col-md-6">
							<input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name Of The Category" />
						</div>
					</div>
					<!-- End Name Field -->
					<!-- Start Description Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">About Vendor</label>
						<div class="col-sm-10 col-md-6">
							<textarea  name="about" class="form-control" placeholder="Describe The Vendor"></textarea>
						</div>
					</div>
					<!-- End Description Field -->
                    <!-- Start Description Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Address</label>
						<div class="col-sm-10 col-md-6">
							<textarea  name="address" class="form-control" placeholder="Full Address"></textarea>
						</div>
					</div>
					<!-- End Description Field -->
					<!-- Start Ordering Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Phone</label>
						<div class="col-sm-10 col-md-6">
							<input type="number" name="phone2" class="form-control" placeholder="Enter Phone Number" />
						</div>
					</div>
					<!-- End Ordering Field -->
					<!-- Start Category Type -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Vendor Category</label>
						<div class="col-sm-10 col-md-6">
							<select name="type" class="form-control">
								<option value="Service Delivery">Service Delivery</option>
								<option value="Real Estate">Real Estate</option>
                                <option value="Retail Business">Retail Business</option>
                                <option value="Transport Business">Transport Business</option>
							</select>
						</div>
					</div>
					<!-- End Category Type -->
					<!-- Start Visibility Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Does your business has a physical office</label>
						<div class="col-sm-10 col-md-6">
							<div>
								<input id="vis-yes" type="radio" name="office" value="0" checked />
								<label for="vis-yes">Yes</label> 
							</div>
							<div>
								<input id="vis-no" type="radio" name="office" value="1" />
								<label for="vis-no">No</label> 
							</div>
						</div>
					</div>
                    <!-- Start Ordering Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Logo</label>
						<div class="col-sm-10 col-md-6">
							<input type="file" name="logo" class="form-control"/>
						</div>
					</div>
					<!-- End Ordering Field -->
					<!-- End Visibility Field -->
					<!-- Start Commenting Field -->
					<!-- <div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Allow Commenting</label>
						<div class="col-sm-10 col-md-6">
							<div>
								<input id="com-yes" type="radio" name="commenting" value="0" checked />
								<label for="com-yes">Yes</label> 
							</div>
							<div>
								<input id="com-no" type="radio" name="commenting" value="1" />
								<label for="com-no">No</label> 
							</div>
						</div>
					</div> -->
					<!-- End Commenting Field -->
					<!-- Start Ads Field -->
					<!-- <div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Allow Ads</label>
						<div class="col-sm-10 col-md-6">
							<div>
								<input id="ads-yes" type="radio" name="ads" value="0" checked />
								<label for="ads-yes">Yes</label> 
							</div>
							<div>
								<input id="ads-no" type="radio" name="ads" value="1" />
								<label for="ads-no">No</label> 
							</div>
						</div>
					</div> -->
					<!-- End Ads Field -->
					<!-- Start Submit Field -->
					<div class="form-group form-group-lg">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" value="Add Category" class="btn btn-primary btn-lg" />
						</div>
					</div>
					<!-- End Submit Field -->
				</form>
			</div>

			<?php

		}elseif ($do == 'Insert') {

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				echo "<h1 class='text-center'>Insert Vendor</h1>";
				echo "<div class='container'>";

				// Get Variables From The Form

				$name 		= $_POST['name'];
				$about 		= $_POST['about'];
				$type 	    = $_POST['type'];
				$office 	= $_POST['office'];
				$address 	= $_POST['address'];
				$phone2 	= $_POST['phone2'];
				$logo 		= $_FILES['logo'];

				// Check If Category Exist in Database

				$check = check("name", "vendors", $name);

				if ($check) {
					$theMsg = '<div class="alert alert-danger">Sorry This Category Is Exist</div>';
					redirect($theMsg, 'back');
				} else {
                $formErrors = array();
                    	// Upload Variables

				$logoName = $_FILES['logo']['name'];
				$logoSize = $_FILES['logo']['size'];
				$logoTmp	= $_FILES['logo']['tmp_name'];
				$logoType = $_FILES['logo']['type'];

				// List Of Allowed File Typed To Upload
				
				$path = $_FILES['logo']['name'];
				$logoAllowedExtension = array("jpeg", "jpg", "png", "gif");
                $extension = pathinfo($path, PATHINFO_EXTENSION);

                if (! empty($extension) && ! in_array($extension, $logoAllowedExtension)) {
					$formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
				}

				if (empty($logoName)) {
					$formErrors[] = 'Avatar Is <strong>Required</strong>';
				}

				if ($logoSize > 4194304) {
					$formErrors[] = 'Avatar Cant Be Larger Than <strong>4MB</strong>';
				}

                

				// Get Avatar Extension


                foreach($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}

                if (empty($formErrors)) {
					// Insert Category Info In Database
                    $logo = rand(0, 10000000000) . '.' . $extension;

					move_uploaded_file($logoTmp, "uploads\\vendors\\" . $logo);

					$stmt = $con->prepare("INSERT INTO 

						vendors(name, about, type, office, address, phone2, logo,user_id,created_at)

					VALUES(:zname, :zabout, :ztype, :zoffice, :zaddress, :zphone2, :zlogo, :zuserid, now())");

					$stmt->execute(array(
						'zname' 	=> $name,
						'zabout' 	=> $about,
						'ztype' 	=> $type,
						'zoffice' 	=> $office,
						'zaddress' 	=> $address,
						'zphone2' 	=> $phone2,
						'zlogo'		=> $logo,
						'zuserid'   => $_SESSION['uid']
					));

					// Echo Success Message

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';

					redirect($theMsg, 'back');

				}
            }
			} else {

				echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

				redirect($theMsg, 'back');

				echo "</div>";

			}

			echo "</div>";

		}  elseif ($do == 'Edit') {


		} elseif ($do == 'Update') {


		} elseif ($do == 'Delete') {


		} elseif ($do == 'Activate') {


		}

		include $tpl . 'footer.php';

	} else {

		header('Location: index.php');

		exit();
	}

	ob_end_flush(); // Release The Output

?>