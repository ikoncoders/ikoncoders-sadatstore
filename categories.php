<?php 
	include 'init.php';
?>

<div class="container">
	<h1 class="text-center">Show Category Items</h1>
	<div class="row">
		<?php
		if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])) {
			$category = intval($_GET['pageid']);
			$allItems = all("*", "items", "where id = {$category}", "AND approve = 1", "id");
			foreach ($allItems as $item) {
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
			echo 'You Must Add Page ID';
		}
		?>
	</div>
</div>

<?php include $tpl . 'footer.php'; ?>