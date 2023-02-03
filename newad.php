<?php
	ob_start();

	$pageTitle = 'Create New Item';
	include 'init.php';
	if (isset($_SESSION['USERNAME'])) {

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$formErrors = array();

			$name 		= filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$desc 		= filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$price 		= filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
			$country 	= filter_var($_POST['country'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$status 	= filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
			$category 	= filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
			$tags 		= filter_var($_POST['tags'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            //image 
            $imageName = $_FILES['image']['name'];
            $imageSize = $_FILES['image']['size'];
            $imageTmp  = $_FILES['image']['tmp_name'];
            $imageType = $_FILES['image']['type'];

            // List Of Allowed File Typed To Upload

            $imageAllowedExtension = array("jpeg", "jpg", "png", "gif");

            // Get Avatar Extension
            $path = $_FILES['image']['name'];

            $extension = pathinfo($path, PATHINFO_EXTENSION);
                

			if (strlen($name) < 4) {
				$formErrors[] = 'Item Title Must Be At Least 4 Characters';
			}

			if (strlen($desc) < 10) {
				$formErrors[] = 'Item Description Must Be At Least 10 Characters';
			}

			if (strlen($country) < 2) {
				$formErrors[] = 'Item Title Must Be At Least 2 Characters';
			}

			if (empty($price)) {
				$formErrors[] = 'Item Price Cant Be Empty';
			}

			if (empty($status)) {
				$formErrors[] = 'Item Status Cant Be Empty';
			}

			if (empty($category)) {
				$formErrors[] = 'Item Category Cant Be Empty';
			}

			// Check If There's No Error Proceed The Update Operation
            if (! empty($extension) && ! in_array($extension, $imageAllowedExtension)) {
                $formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
            }
            if (empty($imageName)) {
                $formErrors[] = 'Avatar Is <strong>Required</strong>';
            }
            if ($imageSize > 4194304) {
                $formErrors[] = 'Avatar Cant Be Larger Than <strong>4MB</strong>';
            }

			if (empty($formErrors)) {
				// Insert Userinfo In Database
                $image = rand(0, 10000000000) . '.' . $extension;

				move_uploaded_file($imageTmp, "admin\\uploads\items\\" . $image);

				$stmt = $con->prepare("INSERT INTO 
					items(name, description, price, country_made, image, status, created_at, cat_id, user_id, tags)
					VALUES(:zname, :zdesc, :zprice, :zcountry, :zimage, :zstatus, now(), :zcat, :zmember, :ztags)");
				$stmt->execute(array(
					'zname' 	=> $name,
					'zdesc' 	=> $desc,
					'zprice' 	=> $price,
					'zcountry' 	=> $country,
                    'zimage'    =>  $image,
					'zstatus' 	=> $status,
					'zcat'		=> $category,
					'zmember'	=> $_SESSION['uid'],
					'ztags'		=> $tags
				));

				// Echo Success Message
				if ($stmt) {
					$succesMsg = 'Item Has Been Added';					
				}
			}
		}

?>
<h1 class="text-center"><?php echo $pageTitle ?></h1>
<div class="create-ad block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading"><?php echo $pageTitle ?></div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-8">
						<form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
							<!-- Start Name Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Name</label>
								<div class="col-sm-10 col-md-9">
									<input 
										pattern=".{4,}"
										title="This Field Require At Least 4 Characters"
										type="text" 
										name="name" 
										class="form-control live"  
										placeholder="Name of The Item"
										data-class=".live-title"
										required />
								</div>
							</div>
							<!-- End Name Field -->
							<!-- Start Description Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Description</label>
								<div class="col-sm-10 col-md-9">
									<input 
										pattern=".{10,}"
										title="This Field Require At Least 10 Characters"
										type="text" 
										name="description" 
										class="form-control live"   
										placeholder="Description of The Item" 
										data-class=".live-desc"
										required />
								</div>
							</div>
							<!-- End Description Field -->
							<!-- Start Price Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Price</label>
								<div class="col-sm-10 col-md-9">
									<input 
										type="text" 
										name="price" 
										class="form-control live" 
										placeholder="Price of The Item" 
										data-class=".live-price" 
										required />
								</div>
							</div>
							<!-- End Price Field -->
							<!-- Start Country Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Country</label>
								<div class="col-sm-10 col-md-9">
									<input 
										type="text" 
										name="country" 
										class="form-control" 
										placeholder="Country of Made" 
										required />
								</div>
							</div>
							<!-- End Country Field -->
							<!-- Start Status Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Status</label>
								<div class="col-sm-10 col-md-9">
									<select name="status" required>
										<option value="">...</option>
										<option value="1">New</option>
										<option value="2">Like New</option>
										<option value="3">Used</option>
										<option value="4">Very Old</option>
									</select>
								</div>
							</div>
							<!-- End Status Field -->
							<!-- Start Categories Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Category</label>
								<div class="col-sm-10 col-md-9">
									<select name="category" required>
										<option value="">...</option>
										<?php
											$cats = all('*' ,'categories', '', '', 'id');
											foreach ($cats as $cat) {
												echo "<option value='" . $cat['id'] . "'>" . $cat['name'] . "</option>";
											}
										?>
									</select>
								</div>
							</div>
							<!-- End Categories Field -->
							<!-- Start Tags Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Tags</label>
								<div class="col-sm-10 col-md-9">
									<input 
										type="text" 
										name="tags" 
										class="form-control" 
										placeholder="Separate Tags With Comma (,)" />
								</div>
							</div>
							<!-- End Tags Field -->
                            <div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">Upload Item Image</label>
								<div class="col-sm-10 col-md-9">
                                <input id="fileUpload" type="file" name="image"/>
								</div>
							</div>
							<!-- Start Submit Field -->
							<div class="form-group form-group-lg">
								<div class="col-sm-offset-3 col-sm-9">
									<input type="submit" value="Add Item" class="btn btn-primary btn-sm" />
								</div>
							</div>
							<!-- End Submit Field -->
						</form>
					</div>
					<div class="col-md-4">
						<div class="thumbnail item-box live-preview">
							<span class="price-tag">
								$<span class="live-price">0</span>
							</span>

                                <div id="wrapper">                                    
                                    <br />
                                    <div id="image-holder"></div>
                                </div> 
                    

							<div class="caption">
								<h3 class="live-title">Title</h3>
								<p class="live-desc">Description</p>
							</div>
						</div>
					</div>
				</div>
				<!-- Start Loopiong Through Errors -->
				<?php 
					if (! empty($formErrors)) {
						foreach ($formErrors as $error) {
							echo '<div class="alert alert-danger">' . $error . '</div>';
						}
					}
					if (isset($succesMsg)) {
						echo '<div class="alert alert-success">' . $succesMsg . '</div>';
					}
				?>
				<!-- End Loopiong Through Errors -->
			</div>
		</div>
	</div>
</div>
<?php
	} else {
		header('Location: login.php');
		exit();
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>

   
<script>
$("#fileUpload").on('change', function () {

    var imgPath = $(this)[0].value;
    var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();

    if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
        if (typeof (FileReader) != "undefined") {

            var image_holder = $("#image-holder");
            image_holder.empty();

            var reader = new FileReader();
            reader.onload = function (e) {
                $("<img />", {
                    "src": e.target.result,
                        "class": "thumb-image"
                }).appendTo(image_holder);

            }
            image_holder.show();
            reader.readAsDataURL($(this)[0].files[0]);
        } else {
            alert("This browser does not support FileReader.");
        }
    } else {
        alert("Pls select only images");
    }
});
</script>