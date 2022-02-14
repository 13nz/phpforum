<?php
include("functions.php");
$id = $_GET["id"];
  $pageTitle = itemFromDB("SELECT name FROM categories WHERE id='$id'", "name");
  include("header.php");
  if(isset($_GET["id"])) {
    $id = $_GET["id"];
    $numSQL = "SELECT COUNT(*) as num FROM posts WHERE category_id='$id'";
    $catname = itemFromDB("SELECT name FROM categories WHERE id='$id'", "name");
    $crumbs=['<a href="index.php" class="link-secondary breadlink" style="text-decoration: none">Categories</a>', "$catname"];
    include_once("breadcrumb.php");


?>



<div class="container">

  <div class="row">
    <div class="col-5 col-lg-3">
      <?php if(isLoggedIn()) { ?>
        <div class="d-grid gap-2 d-md-block">
      <button  class="btn btn-outline-secondary mb-3" role="button" aria-disabled="true"> <a href="newpost.php?cat=<?= $_GET["id"] ?>" style="text-decoration: none" class="butlink">Create a post</a>
      </div>
    <?php } ?>
    </div>
  </div>

  <div class="row my-3">
    <div class="col-12">
      <div class="container tbl tbody mb-2" id="table">
          <div class="row thead my-3 mx-1">
            <div class="col "><b>Post name</b></div>
            <div class="col "><b>Original poster</b></div>
            <div class="col hideme"><b>Replies</b></div>
            <div class="col hideme"><b>Date posted</b></div>
            <div class="col hideme "><b>Last reply</b></div>
          </div>
          <div class="mb-3">
        <?php
        if(!isset($_GET["page"])) {

          $posts = selectFromDB("SELECT * FROM posts  A  LEFT JOIN (SELECT post_id, MAX(datereplied) as latest FROM replies GROUP BY post_id) B ON A.id = B.post_id WHERE category_id='$id' ORDER BY latest DESC, dateposted DESC LIMIT 10", true);
        } elseif(isset($_GET["page"])) {
          $page = $_GET["page"];
          $hide = ($page - 1) * 10;
          $posts = selectFromDB("SELECT * FROM posts  A  LEFT JOIN (SELECT post_id, MAX(datereplied) as latest FROM replies GROUP BY post_id) B ON A.id = B.post_id WHERE category_id='$id' ORDER BY latest DESC, dateposted DESC LIMIT $hide, 10", true);
        }

        foreach($posts as $post) {
          $repNum = getReplyCount($post->id, 'replies');
          $user = itemFromDB("SELECT username FROM users WHERE id='$post->user_id'", "username");
          $replies = selectFromDB("SELECT datereplied FROM replies WHERE post_id='$post->id' ORDER BY datereplied DESC", true);
          if(count($replies) > 0) {
            $lastreply = $replies[0];
          };
         ?>
         <hr>
          <div class="row my-3 mx-1">
            <div class="col truncate"><a href="post.php?id=<?= $post->id ?>" class="catlink tablink"><?= $post->title ?></a></div>
            <div class="col truncate"><a href="profile.php?user=<?= $user ?>" class="catlink"> @<?= $user ?></a></div>
            <div class="col hideme"><?= $repNum ?></div>
            <div class="col hideme"><?= $post->dateposted ?></div>
            <div class="col hideme truncate"><?php if(count($replies) > 0) { echo $lastreply->datereplied; } ?></div>
          </div>
        <?php } ?>
      </div>
      </div>
    </div>
    </div>


  <?php include("pagination.php"); ?>
</div>
</div>



</div>
<?php }

include("footer.php"); ?>
