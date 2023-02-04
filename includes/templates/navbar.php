<div class="upper-bar">
		<div class="container">
		<li><a href="logout.php">Logout</a></li>
			<?php 
				if (isset($_SESSION['uid'])) { ?>

				<img class="my-image img-thumbnail img-circle" src="img.png" alt="" />
				<div class="btn-group my-info">
					<span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						<?php echo $sessionUser ?>
						<span class="caret"></span>
					</span>
					<ul class="dropdown-menu">
						<li><a href="profile.php">My Profile</a></li>
						<li><a href="newad.php">New Item</a></li>
						<li><a href="profile.php#my-ads">My Items</a></li>
						<li><a href="logout.php">Logout</a></li>
					</ul>
				</div>

				<?php

				} else {
			?>
			<a href="login.php">
				<span class="pull-right">Login | Signup</span>
			</a>
			<?php } ?>
		</div>
	</div>
	<nav class="navbar navbar-inverse">
	  <div class="container">
	    <div class="navbar-header">
	      <button 
	      		type="button" 
	      		class="navbar-toggle collapsed" 
	      		data-toggle="collapse" 
	      		data-target="#app-nav" 
	      		aria-expanded="false">
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="index.php">Store</a>
	    </div>
	    <div class="collapse navbar-collapse" id="app-nav">
	      <ul class="nav navbar-nav navbar-right">
	      <?php
	      	$allCats = all("*", "categories", "where parent = 0", "", "id", "ASC");
			foreach ($allCats as $cat) {
				echo 
				'<li>
					<a href="categories.php?pageid=' . $cat['id'] . '">
						' . $cat['name'] . '
					</a>
				</li>';
			}
	      ?>
	      </ul>
	    </div>
	  </div>
	</nav>