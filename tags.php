<?php 
	
	include 'init.php';
?>

<div class="container">
	<div class="row">
		<?php
		if (isset($_GET['name'])) {
			$tag = $_GET['name'];
			echo "<h1 class='text-center'>" . $tag . "</h1>";
			$tagItems = all("*", "items", "where tags like '%$tag%'", "AND approve = 1", "id");
			foreach ($tagItems as $item) {
				echo '<div class="col-sm-6 col-md-3">';
					echo '<div class="thumbnail item-box">';
						echo '<span class="price-tag">' . $item['price'] . '</span>';
						echo '<img class="img-responsive" src="admin/uploads/items/'.$item['image'].'" alt="" />';
						echo '<div class="caption">';
							echo '<h3><a href="items.php?itemid='. $item['id'] .'">' . $item['name'] .'</a></h3>';
							echo '<p>' . $item['description'] . '</p>';
							echo '<div class="date">' . $item['created_at'] . '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}
		} else {
			echo 'You Must Enter Tag Name';
		}
		?>
	</div>
</div>

<?php include $tpl . 'footer.php'; ?>