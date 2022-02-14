<?php include("header.php");
  $catID = $_GET["cat"];
  $catname = itemFromDB("SELECT name FROM categories WHERE id='$catID'", "name");

  $crumbs = ['<a href="index.php" class="link-secondary breadlink" style="text-decoration: none">Categories</a>', "<a href='category.php?id=$catID' class='link-secondary breadlink' style='text-decoration: none'>$catname</a>", "New post"];
  include_once("breadcrumb.php");
?>


<div class="container">
  <div class="row justify-content-center">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <form method="POST" action="actions.php?newpost=true&cat=<?= $_GET["cat"] ?>" enctype="multipart/form-data">
        <div class="form-group my-3">
          <label>Title</label>
          <input name="title" type="text" class="form-control inputbg" id="posttitle" placeholder="Post title" required>
        </div>
        <div class="form-group my-3">
          <label>Body</label>
          <textarea name="body" class="form-control inputbg" id="postbody" rows="3" required></textarea>
        </div>
        <div class="form-group my-3">
          <input name="image" type="file" class="form-control-file" id="postfile">
        </div>
        <button type="submit" class="btn btn-outline-secondary my-3">Publish</button>
      </form>
    </div>
    <?php
    if(isset($_SESSION["newPostMsg"])) {
    foreach($_SESSION["newPostMsg"] as $msg) {
    	echo errorAlert($msg);
    		};
    	};
      ?>
  </div>
</div>
</div>



<?php include("footer.php"); ?>
