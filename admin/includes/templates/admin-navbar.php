<nav class="navbar navbar-inverse">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="dashboard.php"><?php echo lang('HOME_ADMIN') ?></a>
    </div>
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="nav navbar-nav">
        <li><a href="categories.php"><?php echo lang('CATEGORIES') ?></a></li>
        <li><a href="items.php"><?php echo lang('ITEMS') ?></a></li>
        <li><a href="users.php"><?php echo lang('USERS') ?></a></li>
        <li><a href="vendors.php"><?php echo lang('VENDORS') ?></a></li>
        <li><a href="comments.php"><?php echo lang('COMMENTS') ?></a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <?= $_SESSION['ADMIN']?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="../index.php">Visit Shop</a></li>
            <li><a href="members.php?do=Edit&userid=<?php echo $_SESSION['ADMIN'] ?>">Edit Profile</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>