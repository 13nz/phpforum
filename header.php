<?php
require_once("functions.php");
require('vendor/autoload.php');




if(!isset($pageTitle)) {
	$pageTitle = "Home";
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width" />
	<title><?= $pageTitle ?></title>
	<link href="/css/all.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link id="theme" rel='stylesheet' href="css/default.css">

	<script
  src="https://code.jquery.com/jquery-3.6.0.js"
  integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
  crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>

  <script src="js/script.js"></script>
</head>

<body onload="setFromCookie()">
  <!-- HEADER -->
  <div class="container-fluid" id="header">
    <div class="row">
      <div class="col-6 col-sm-6 col-md-8 col-lg-9" id="logo">
        <a href="index.php" style="text-decoration: none;"><h1 id="logo" class="my-3">fnord</h1></a>
      </div>
      <div class="col-6 col-sm-6 col-md-4 col-lg-3" id="search">
        <form class="d-flex my-4" method="post" action="actions.php?search=site">
          <input name="searchSite" class="form-control me-2 inputbg" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-secondary" type="submit">Search</button>
        </form>
      </div>
    </div>
  </div>


  <!-- MAIN -->
  <div class="container-fluid" id="main">

    <div class="container">
      <div class="row my-3 menustyle op"  style="height: auto;">
        <div class="col-12 py-1">
          <?php include("nav.php"); ?>
        </div>
      </div>
    </div>
