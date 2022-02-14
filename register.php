<?php
require_once("header.php");
 ?>


<div class="container">


  <?php if(!isset($_GET["login"])) {
    $pageTitle = "Register";
    ?>
  <div class="row justify-content-center">
    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
     <form action="actions.php?form=register" method="POST">
       <div class="form-group my-2">
         <label style="color: #b7b2c5">Username</label>
         <input name="username" type="text" class="form-control my-1 inputbg" placeholder="Username" required>
       </div>
       <div class="form-group">
         <label style="color: #b7b2c5">Email address</label>
         <input name="email" type="email" class="form-control my-1 inputbg" aria-describedby="emailHelp" placeholder="Enter your email" required>
       </div>
       <div class="form-group my-2">
         <label style="color: #b7b2c5">Create password</label>
         <input name="pass1" type="password" class="form-control my-1 inputbg" placeholder="Password" required>
       </div>
       <div class="form-group my-2">
         <label style="color: #b7b2c5">Confirm your password</label>
         <input name="pass2" type="password" class="form-control my-1 inputbg" placeholder="Confirm password" required>
       </div>
       <button type="submit" class="btn btn-outline-secondary my-2">Sign up</button>
     </form>
    </div>
  </div>

</div>
<?php
if(isset($_SESSION["message"])) {
foreach($_SESSION["message"] as $msg) {
	echo errorAlert($msg);
		};
	};
  };
?>






<?php if(isset($_GET["login"]) && $_GET["login"] == TRUE) {
  $pageTitle = "Log in";
   ?>
<div class="row justify-content-center">
  <div class="col-12 col-sm-12 col-md-6 col-lg-4">
   <form action="actions.php?form=login" method="POST">
     <div class="form-group my-2">
       <label style="color: #b7b2c5">Username</label>
       <input name="username" type="text" class="form-control my-1 inputbg" placeholder="Username" required>
     </div>
     <div class="form-group my-2">
       <label style="color: #b7b2c5">Password</label>
       <input name="password" type="password" class="form-control my-1 inputbg" placeholder="Password" required>
     </div>
     <button type="submit" class="btn btn-outline-secondary my-2">Log in</button>
   </form>
  </div>
</div>
<?php

if (isset($_SESSION["loginMessage"])) {
	echo errorAlert($_SESSION["loginMessage"]);
};
};
?>

</div>
</div>


<?php include("footer.php"); ?>
