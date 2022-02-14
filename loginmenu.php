<?php
include_once("functions.php"); ?>


<div class="container-fluid">
	<div class="row" id="loginmenu">
			<ul class="nav">
				<?php if(!isLoggedIn()) { ?>
				<li class="nav-item">
					<a class="nav-link" aria-current="page" href="register.php">Register</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" aria-current="page" href="register.php?login=true">Log in</a>
				</li>
			<?php };
				if(isLoggedIn()) { ?>
        <li class="nav-item">
					<a class="nav-link" aria-current="page" href="logout.php">Log out</a>
				</li>
			<?php
			if($_SESSION["userRole"] == "1") { ?>
				<li class="nav-item">
					<a class="nav-link" aria-current="page" href="dashboard.php">Dashboard</a>
				</li>
			<?php }
				}?>
			</ul>
    </div>
  </div>
