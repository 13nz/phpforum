<?php
session_start();
include_once("functions.php"); ?>






<nav class="navbar navbar-expand-md navbar-light">
  <div class="container-fluid" id="menu">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></i></span>
    </button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item pe-2">
          <a class="nav-link" aria-current="page" href="index.php">Home</a>
        </li>
				<?php if(isLoggedIn()) {?>
				<li class="nav-item pe-2">
					<a class="nav-link" aria-current="page" href="profile.php?user=<?= $_SESSION["loggedInUser"]->username ?>">Profile</a>
				</li>
			<?php } ?>
			<?php if(!isLoggedIn()) { ?>

			<li class="nav-item pe-2">
				<a class="nav-link" aria-current="page" href="register.php">Register</a>
			</li>
			<li class="nav-item pe-2">
				<a class="nav-link" aria-current="page" href="register.php?login=true">Log in</a>
			</li>
		<?php };
			if(isLoggedIn()) { ?>
			<li class="nav-item pe-2">
				<a class="nav-link" aria-current="page" href="logout.php">Log out</a>
			</li>
		<?php
		if($_SESSION["userRole"] == "1") { ?>
			<li class="nav-item pe-2">
				<a class="nav-link" aria-current="page" href="dashboard.php">Dashboard</a>
			</li>
		<?php }
			}?>
      </ul>
    </div>
  </div>
</nav>
