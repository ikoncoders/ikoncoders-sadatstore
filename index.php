<?php
	ob_start();
	
	$pageTitle = 'Sadat-Store';
	include 'init.php';
?><br>
<div class="container">
	<div class="row">
		<?php
			$allItems = all('*', 'items', 'where approve = 1', '', 'id');
			foreach ($allItems as $item) {
				echo '<div class="col-sm-6 col-md-3">';
					echo '<div class="thumbnail item-box">';
						echo '<span class="price-tag">$' . $item['price'] . '</span>';
						echo '<img class="img-responsive" src="admin/uploads/items/'.$item['image'].'" alt="" />';
						echo '<div class="caption">';
							echo '<h3><a href="items.php?itemid='. $item['id'] .'">' . $item['name'] .'</a></h3>';
							echo '<p>' . $item['description'] . '</p>';
							echo '<div class="date">' . $item['created_at'] . '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}
		?>
	</div>
</div>
<?php
	include $tpl . 'footer.php'; 
	ob_end_flush();
?>