<?php include("header.php");
      $conn = connectToDB();
 ?>




<div class="container">



<?php if(isset($_GET["item"])) {
  $item = mysqli_real_escape_string($conn, $_GET["item"]);
  $users = selectFromDB("SELECT * FROM users WHERE username='$item'", true);
  $posts = SelectFromDB("SELECT * FROM posts WHERE title LIKE '%$item%'", true);
  $replies = selectFromDB("SELECT * FROM replies WHERE body LIKE '%$item%'", true);

  ?>



  <!-- USERS -->
  <?php if(count($users) > 0) { ?>
  <div class="row my-5">
    <div class="col-12">
      <div class="container tbody tbl">
          <div class="row thead my-3 mx-1">
            <div class="col"><b>Users</b></div>
            <div class="col"><b>Posts</b></div>
            <div class="col hideme"><b>Replies</b></div>
          </div>
          <div class="mb-3">
          <?php foreach($users as $user) {
            $userPosts = getCount("SELECT COUNT(*) AS num FROM posts WHERE user_id='$user->id'");
            $userReplies = getCount("SELECT COUNT(*) as num FROM replies WHERE user_id='$user->id'");
             ?>
             <hr>
          <div class="row my-3 mx-1">
            <div class="col"><a href="profile.php?user=<?= $user->username ?>" class="catlink" ><?= $user->username ?></a></div>
            <div class="col"><?= $userPosts ?></div>
            <div class="col hideme"><?= $userReplies ?></div>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
  </div>
<?php } ?>





  <!-- POSTS -->
  <?php if(count($posts) > 0) { ?>
  <div class="row my-5">
    <div class="col-12">
      <div class="container tbody tbl">
          <div class="row thead my-3 mx-1">
            <div class="col" scope="col"><b>Posts</b></div>
            <div class="col" scope="col"><b>Original poster</b></div>
            <div class="col hideme" scope="col"><b>Date posted</b></div>
          </div>
          <div class="mb-3">
          <?php foreach($posts as $post) {
            $op = itemFromDB("SELECT username FROM users WHERE id='$post->user_id'", "username");
            ?>
            <hr>
          <div class="row my-3 mx-1">
            <div class="col truncate"><a href="post.php?id=<?= $post->id ?>" class="catlink "><?= $post->title ?></a></div>
            <div class="col"><a href="profile.php?user=<?= $op ?>" class="catlink" ><?= $op ?></a></div>
            <div class="col hideme"><?= $post->dateposted ?></div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
<?php } ?>




<?php if(count($users) == 0 && count($posts) == 0 && count($replies) == 0) { ?>


  <div class="container">
    <div class="container">
      <div class="card text-center cardbody">
        <div class="card-header cardhead">
          No results
        </div>
        <div class="card-body">
          <h5 class="card-title">No results containing "<?= $item ?>"</h5>
          <a href="index.php" class="btn butt my-2">Go back</a>
        </div>
      </div>
    </div>
  </div>


<?php } ?>





<?php } ?>

</div>
