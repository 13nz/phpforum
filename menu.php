<?php
session_start();
include_once("functions.php"); ?>


<div class="container-fluid">
	<div class="row" id="menu">
			<ul class="nav">
				<li class="nav-item">
					<a class="nav-link" aria-current="page" href="index.php">Home</a>
				</li>
				<?php if(isLoggedIn()) {?>
				<li class="nav-item">
					<a class="nav-link" aria-current="page" href="profile.php?user=<?= $_SESSION["loggedInUser"]->username ?>">Profile</a>
				</li>
			<?php } ?>
			</ul>
    </div>
  </div>
