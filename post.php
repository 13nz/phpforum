<?php
  include("functions.php");
  $id = $_GET["id"];
  $post = selectFromDB("SELECT * FROM posts WHERE id='$id'", true)[0];
  $pageTitle = $post->title;

  include("header.php");

  if(isset($_GET["id"])) {
    $id = $_GET["id"];
    $post = selectFromDB("SELECT * FROM posts WHERE id='$id'", true)[0];
    $userid = itemFromDB("SELECT user_id FROM posts WHERE id='$id'", "user_id");
    $op = selectFromDB("SELECT * FROM users WHERE id='$userid'", true)[0];
    $numSQL = "SELECT COUNT(*) as num FROM replies WHERE post_id='$id'";
    $catID = $post->category_id;
    $catname = itemFromDB("SELECT name FROM categories WHERE id='$post->category_id'", "name");
    $crumbs = ['<a href="index.php" class="link-secondary breadlink" style="text-decoration: none">Categories</a>', "<a href='category.php?id=$catID' class='link-secondary breadlink' style='text-decoration: none'>$catname</a>"];
    include_once("breadcrumb.php");

  //REPLIES


   ?>


  <!-- OP -->
  <?php if(!isset($_GET["page"]) || $_GET["page"] == 1 || $_GET["page"] == 0) { ?>
  <div class="container op mt-3" style="height: auto;">



    <div class="row justify-content-center ophead" id="<?= $post->id ?>" style="height: auto;">
      <h4 class=" px-1 py-1 ps-2"><?= str_replace(["\r\n", "\r", "\n","\\r","\\n","\\r\\n"], "<br />", $post->title); ?></h4>
    </div>
    <div class="row justify-content-center">
      <div class="col-12 col-sm-12 col-md-2 col-lg-1 opprofile py-2">
        <div class="row mb-1">
        <div class="col-2 col-sm-2 col-md-12 col-lg-12">
          <a href="profile.php?user=<?= $op->username ?>" class="oplink"><img src="<?= $op->avatar ?>" class="img-fluid av"></a>
        </div>

        <div class="col-2 col-sm-2 col-md-12 col-lg-12">
        <p><a href="profile.php?user=<?= $op->username ?>" class="oplink">@<?= $op->username ?></a></p>
      </div>
        <?php if(isLoggedIn() && ($_SESSION["userRole"] === '1' || $op->id === $_SESSION["userID"])) { ?>
          <div class="col-8 col-sm-8 col-md-12 col-lg-12 text-end">

            <a href="actions.php?delete=post&id=<?= $post->id ?>" class="oplink" style="font-size: 0.7rem;">Delete post</a>
          </div>
        <?php } ?>
      </div>
    </div>
      <div class="col-12 col-sm-12 col-md-10 col-lg-11 opbody py-3">
        <div class="row">
          <div class="col-4 col-sm-4 col-md-12 col-lg-12">
            <p class="postid"><a href="#<?= $post->id ?>" class="catlink">>><?= $post->id ?></a></p>
          </div>
          <hr>
        </div>
        <p class=" px-1 py-1"><?= str_replace(["\r\n", "\r", "\n","\\r","\\n","\\r\\n"], "<br />", $post->body); ?></p>
        <!-- IF IMAGE -->
        <?php if($post->file !== NULL) { ?>
        <img src="<?= $post->file ?>" class="img-fluid">
        <!-- END IF -->
      <?php } ?>
      <hr>
      <p class="postid"><?= $post->dateposted; ?></p>
      </div>
    </div>

  </div>
<?php } ?>


  <!-- REPLIES -->
  <?php
  if(!isset($_GET["page"])) {
    $replies = selectFromDB("SELECT * FROM replies WHERE post_id='$id' LIMIT 10", true);
  } elseif(isset($_GET["page"])) {
    $page = (int) $_GET["page"];
    $hide = ($page - 1) * 10;
    $replies = selectFromDB("SELECT * FROM replies WHERE post_id='$id' LIMIT $hide ,10", true);
  }



  if(count($replies) > 0) {
    foreach($replies as $reply) {
      $thisID = $reply->user_id;
      $username = itemFromDB("SELECT username FROM users WHERE id='$thisID'", 'username');
      $avatar = itemFromDB("SELECT avatar FROM users WHERE id='$thisID'", 'avatar');
       ?>


  <!-- FOREACH -->
  <div class="container reply">

    <div class="row justify-content-center" id="<?= $reply->id ?>">
      <div class="col-12 col-sm-12 col-md-2 col-lg-1 replyprofile">
        <div class="row mb-2">
        <div class="col-2 col-sm-2 col-md-12 col-lg-12 mt-1">
          <a href="profile.php?user=<?= $username ?>" class="catlink replink"><img src="<?= $avatar ?>" class="img-fluid mt-2 av"></a>
        </div>
        <div class="col-2 col-sm-2 col-md-12 col-lg-12 mt-2">
        <p><a href="profile.php?user=<?= $username ?>" class="catlink replink">@<?= $username ?></a></p>
      </div>
        <?php if(isLoggedIn() && ($thisID === $_SESSION["userID"] || $_SESSION["userRole"] === '1')) { ?>
          <div class="col-8 col-sm-8 col-md-12 col-lg-12 mt-2 text-end">
        <a href="actions.php?delete=reply&id=<?= $reply->id ?>" class="catlink replink" style="font-size: 0.7rem;">Delete</a>
      </div>
        <?php } ?>
      </div>
      </div>
      <div class="col-12 col-sm-12 col-md-10 col-lg-11 replybody py-3">
        <div class="row">
          <div class="col-4 col-sm-4 col-md-12 col-lg-12">
            <p class="postid"><a href="#<?= $reply->id ?>" class="catlink">>><?= $reply->id ?></a></p>
          </div>
          <hr>
        </div>
        <p class=" px-1 py-1"><?= str_replace(["\r\n", "\r", "\n","\\r","\\n","\\r\\n"], "<br />", $reply->body); ?></p>
        <!-- IF IMAGE -->
        <?php if($reply->file !== "NULL") { ?>
        <img src="<?= $reply->file ?>" class="img-fluid">
        <!-- END IF -->
      <?php } ?>
      <hr>
      <p class="postid"><?= $reply->datereplied; ?></p>
      </div>
    </div>


  <!-- END FOREACH -->

</div>
<?php }} ?>



  <!-- REPLY -->
  <?php if(isLoggedIn()) {
    $numrows = getCount($numSQL);
    $lastpage = ceil($numrows / 10);
     ?>
  <div class="container addreply">
    <form method="POST" action="actions.php?reply=true&post=<?= $id ?>&page=<?= $lastpage ?>" enctype="multipart/form-data">
      <div class="form-floating mt-5">
        <textarea name="reply" class="form-control inputbg" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px" required></textarea>
        <label style="color: #201a34">Add a reply</label>
      </div>
      <div class="form-group my-2">
        <input name="image" type="file" class="form-control-file" id="postfile">
      </div>
      <button type="submit" class="btn btn-outline-secondary my-2">Post</button>
    </form>
  <?php }
  if (isset($_SESSION["replyError"]) && isset($_SESSION["replyError"][0])) {
    echo errorAlert($_SESSION["replyError"][0]);
  };

   ?>
  </div>

  <div class="container">
      <!-- IF PAGES -->
      <?php include("pagination.php"); ?>
  </div>
  </div>





</div>
<?php };
  include("footer.php");
 ?>
