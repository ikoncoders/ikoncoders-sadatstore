<?php
	ob_start();

	$pageTitle = 'Profile';
	include 'init.php';
	if (isset($_SESSION['USERNAME'])) {
		$getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");
		$getUser->execute(array($session));
		$info = $getUser->fetch();
		$userid = $info['id'];
?>
<h1 class="text-center">My Profile</h1>
<div class="information block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">My Information</div>
			<div class="panel-body">
				<ul class="list-unstyled">
					<li>
						<i class="fa fa-unlock-alt fa-fw"></i>
						<span>Login Name</span> : <?php echo $info['username'] ?>
					</li>
					<li>
						<i class="fa fa-envelope-o fa-fw"></i>
						<span>Email</span> : <?php echo $info['email'] ?>
					</li>
					<li>
						<i class="fa fa-user fa-fw"></i>
						<span>Full Name</span> : <?php echo $info['fullName'] ?>
					</li>
					<li>
						<i class="fa fa-calendar fa-fw"></i>
						<span>Registered Date</span> : <?php echo $info['created_at'] ?>
					</li>
					<li>
						<i class="fa fa-tags fa-fw"></i>
						<span>Fav Category</span> :
					</li>
				</ul>
				<a href="edit_profile.php?do=Edit&userid=<?=$info['id']?>" class="btn btn-default">Edit Information</a>
               
				<a href="newad.php?do=Add" class="btn btn-primary">	<i class="fa fa-plus"></i> Post New Ad	</a>
				
			</div>
		</div>
	</div>
</div>
<div id="my-ads" class="my-ads block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">My Items</div>
			<div class="panel-body">
			<?php
				$myItems = all("*", "items", "where user_id = $userid", "", "id");
				if (! empty($myItems)) {
					echo '<div class="row">';
					foreach ($myItems as $item) {
						echo '<div class="col-sm-6 col-md-3">';
							echo '<div class="thumbnail item-box">';
								if ($item['approve'] == 0) { 
									echo '<span class="approve-status">Waiting Approval</span>'; 
								}
								echo '<span class="price-tag">$' . $item['price'] . '</span>';
								echo '<img class="img-responsive" src="img.png" alt="" />';
								echo '<div class="caption">';
									echo '<h3><a href="items.php?itemid='. $item['id'] .'">' . $item['name'] .'</a></h3>';
									echo '<p>' . $item['description'] . '</p>';
									echo '<div class="date">' . $item['created_at'] . '</div>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
					}
					echo '</div>';
				} else {
					echo 'Sorry There\' No Ads To Show, Create <a href="newad.php">New Ad</a>';
				}
			?>
			</div>
		</div>
       
	</div>
    
</div>
<div class="my-comments block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">Latest Comments</div>
			<div class="panel-body">
			<?php
				$myComments = all("comment", "comments", "where user_id = $userid", "", "c_id");
				if (! empty($myComments)) {
					foreach ($myComments as $comment) {
						echo '<p>' . $comment['comment'] . '</p>';
					}
				} else {
					echo 'There\'s No Comments to Show';
				}
			?>
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