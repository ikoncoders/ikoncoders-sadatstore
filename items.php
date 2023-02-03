<?php
	ob_start();
	
	$pageTitle = 'Show Items';
	include 'init.php';

	// Check If Get Request item Is Numeric & Get Its Integer Value
	$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

	// Select All Data Depend On This ID
    $stmt = $con->prepare("SELECT 
                                items.*,
                                categories.name AS category_name, 
                                users.username 
                            FROM 
                                items
                            INNER JOIN 
                                users ON items.user_id = users.id
                            INNER JOIN 
                                categories ON items.cat_id = categories.id
                            WHERE 
                                items.id = ? 
                            AND 
                                items.approve = 1");

	// Execute Query
	$stmt->execute(array($itemid));

	$count = $stmt->rowCount();

	if ($count) {

	// Fetch The Data
	$item = $stmt->fetch();
?>
<h1 class="text-center"><?php echo $item['name'] ?></h1>
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<img class="img-responsive img-thumbnail center-block" src="admin/uploads/items/<?=$item['image']?>" alt="" />
		</div>
		<div class="col-md-9 item-info">
			<h2><?php echo $item['name'] ?></h2>
			<p><?php echo $item['description'] ?></p>
			<ul class="list-unstyled">
				<li>
					<i class="fa fa-calendar fa-fw"></i>
					<span>Added Date</span> : <?php echo $item['created_at'] ?>
				</li>
				<li>
					<i class="fa fa-money fa-fw"></i>
					<span>Price</span> : <?php echo $item['price'] ?>
				</li>
				<li>
					<i class="fa fa-building fa-fw"></i>
					<span>Made In</span> : <?php echo $item['country_made'] ?>
				</li>
				<li>
					<i class="fa fa-tags fa-fw"></i>
					<span>Category</span> : <a href="categories.php?pageid=<?php echo $item['id'] ?>"><?php echo $item['category_name'] ?></a>
				</li>
				<li>
					<i class="fa fa-user fa-fw"></i>
					<span>Added By</span> : <a href="#"><?php echo $item['username'] ?></a>
				</li>
				<li class="tags-items">
					<i class="fa fa-user fa-fw"></i>
					<span>Tags</span> : 
					<?php 
						$allTags = explode(",", $item['tags']);
						foreach ($allTags as $tag) {
							$tag = str_replace(' ', '', $tag);
							$lowertag = strtolower($tag);
							if (! empty($tag)) {
								echo "<a href='tags.php?name={$lowertag}'>" . $tag . '</a>';
							}
						}
					?>
				</li>
			</ul>
		</div>
	</div>
	<hr class="custom-hr">
	<?php if (isset($_SESSION['USERNAME'])) { ?>
	<!-- Start Add Comment -->
	<div class="row">
		<div class="col-md-offset-3">
			<div class="add-comment">
				<h3>Add Your Comment</h3>
				<form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['id'] ?>" method="POST">
					<textarea name="comment" required></textarea>
					<input class="btn btn-primary" type="submit" value="Add Comment">
				</form>
				<?php 
					if ($_SERVER['REQUEST_METHOD'] == 'POST') {

						$comment 	= filter_var($_POST['comment'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
						$itemid 	= $item['id'];
						$userid 	= $_SESSION['uid'];

						if (! empty($comment)) {

							$stmt = $con->prepare("INSERT INTO 
								comments(comment, status, comment_date, item_id, user_id)
								VALUES(:zcomment, 1, NOW(), :zitemid, :zuserid)");

							$stmt->execute(array(
								'zcomment' => $comment,
								'zitemid' => $itemid,
								'zuserid' => $userid
							));

							if ($stmt) {
								echo '<div class="alert alert-success">Comment Added</div>';
							}
						} else {
							echo '<div class="alert alert-danger">You Must Add Comment</div>';
						}
					}
				?>
			</div>
		</div>
	</div>
	<!-- End Add Comment -->
	<?php } else {
		echo '<a href="login.php">Login</a> or <a href="login.php">Register</a> To Add Comment';
	} ?>
	<hr class="custom-hr">
		<?php

			// Select All Users Except Admin 
			$stmt = $con->prepare("SELECT 
										comments.*, users.username AS User  
									FROM 
										comments
									INNER JOIN 
										users 
									ON 
										users.id = comments.user_id
									WHERE 
										id = ?
									AND 
										status = 1
									ORDER BY 
										c_id DESC");

			// Execute The Statement

			$stmt->execute(array($item['id']));

			// Assign To Variable 

			$comments = $stmt->fetchAll();

		?>
	
	<?php foreach ($comments as $comment) { ?>
		<div class="comment-box">
			<div class="row">
				<div class="col-sm-2 text-center">
					<img class="img-responsive img-thumbnail img-circle center-block" src="img.png" alt="" />
					<?php echo $comment['User'] ?>
				</div>
				<div class="col-sm-10">
					<p class="lead"><?php echo $comment['comment'] ?></p>
				</div>
			</div>
		</div>
		<hr class="custom-hr">
	<?php } ?>
</div>
<?php
	} else {
		echo '<div class="container">';
			echo '<div class="alert alert-danger">There\'s no Such ID Or This Item Is Waiting Approval</div>';
		echo '</div>';
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>