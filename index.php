<?php
$pageTitle = "Home";
$crumbs = ["Categories"];
require_once("header.php");
require_once("breadcrumb.php");
$categories = selectFromDB("SELECT * FROM categories", true);
 ?>



  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="container tbl tbody" id="table">
            <div class="row thead my-3 mx-1">
              <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"><b>Categories</b></div>
              <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 hideme"><b>Posts</b></div>
              <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 hideme"><b>Replies</b></div>
              <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 hideme"><b>Latest Posts</b></div>
          </div>
          <div   class="mb-3">
          <?php foreach($categories as $category) {
            $postNum = getPostCount($category->id, 'posts');
            if($postNum > 0) {
              $posts = selectFromDB("SELECT * FROM posts WHERE category_id='$category->id' Order By dateposted Desc", true);
              $latestpost = $posts[0]->title;
              $latestID = $posts[0]->id;
              $replies = 0;
              foreach($posts as $post) {
                $repNum = getReplyCount($post->id, 'replies');
                $replies += $repNum;
              }
            } else {
              $latestpost = "";
            };


             ?>
             <hr>
            <div class="row my-3 mx-1">
              <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"><a href="category.php?id=<?= $category->id ?>" class="catlink"><?= $category->name ?></a></div>
              <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 hideme"><?= $postNum ?></div>
              <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 hideme"><?= $replies ?></div>
              <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 hideme truncate"><a href="post.php?id=<?= $latestID ?>" class="catlink"><?= $latestpost ?></a></div>
            </div>

          <?php } ?>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
include("footer.php"); ?>
