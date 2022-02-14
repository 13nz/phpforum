<?php
$pageTitle = "Dashboard";
include("header.php");
$conn = connectToDB();

$userCount = getCount("SELECT COUNT(id) AS num FROM users");
$postCount = getCount("SELECT COUNT(id) AS num FROM posts");

  if(!isset($_SESSION["userRole"]) || $_SESSION["userRole"] !== "1") { ?>

    <div class="container">
      <div class="container">
        <div class="card text-center cardbody">
          <div class="card-header cardhead">
            Restricted
          </div>
          <div class="card-body">
            <h5 class="card-title">Cannot access</h5>
            <a href="index.php" class="btn butt my-2">Go back</a>
          </div>
        </div>
      </div>
    </div>


<!-- DASHBOARD MAIN -->
  <?php } elseif(isset($_SESSION["userRole"]) && $_SESSION["userRole"] === "1") {
    if(!isset($_GET["manage"])) { ?>

    <div class="container">

      <ul class="list-group">
        <li class="list-group-item d-flex justify-content-between align-items-center inputbg">
          <a href="?manage=users" class="catlink">Manage users</a>
          <span class="badge bg-secondary rounded-pill"><?= $userCount ?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center inputbg">
          <a href="?manage=posts" class="catlink">Manage posts</a>
          <span class="badge bg-secondary rounded-pill"><?= $postCount ?></span>
        </li>
      </ul>

    </div>
  <?php } ?>


  <!-- MANAGE USERS -->

  <?php if(isset($_GET["manage"]) && $_GET["manage"] == "users") {
    if(!isset($_GET["search"])) {
      $users = selectFromDB("SELECT * FROM users", true);
    } else {
      $q = mysqli_real_escape_string($conn, $_GET["search"]);
      $users = selectFromDB("SELECT * FROM users WHERE username LIKE '%$q%'", true);
    }
     ?>

    <div class="container">
      <div class="row">
          <div class="col-xs-6 col-lg-3" id="search">
            <form class="d-flex my-4" method="post" action="actions.php?searchUsers=true">
              <input name="searchUsers" class="form-control me-2 inputbg" type="search" placeholder="Search users" aria-label="Search">
              <button class="btn btn-outline-secondary" type="submit">Search</button>
            </form>
          </div>
      </div>


      <?php if(count($users) > 0) { ?>
      <div class="row">
        <div class="col-12">
          <div class="container tbl tbody" id="table">
              <div class="row thead my-3 mx-3">
                <div class="col">User ID</div>
                <div class="col">Username</div>
                <div class="col hideme">Posts</div>
                <div class="col hideme">Replies</div>
                <div class="col">Manage</div>
              </div>
            <div class="mb-3">
              <?php foreach($users as $user) {
                $userPosts = getCount("SELECT COUNT(*) AS num FROM posts WHERE user_id=$user->id");
                $userReplies = getCount("SELECT COUNT(*) AS num FROM replies WHERE user_id=$user->id")
                ?>
                <hr>
              <div class="row my-3 mx-1">
                <div class="col"><?= $user->id ?></div>
                <div class="col"><a href="profile.php?user=<?= $user->username ?>" class="catlink"><?= $user->username ?></a></div>
                <div class="col hideme"><?= $userPosts ?></div>
                <div class="col hideme"><?= $userReplies ?></div>
                <div class="col"><a href="actions.php?admindelete=user&userid=<?= $user->id ?>" class="catlink">Delete user</a></div>
              </div>
            <?php } ?>
          </div>
        </div>
        </div>
      </div>
    <?php } else { ?>
      <div class="container">
        <div class="container">
          <div class="card text-center cardbody">
            <div class="card-header cardhead">
              No results
            </div>
            <div class="card-body">
              <h5 class="card-title">No results containing "<?= $q ?>"</h5>
              <a href="dashboard.php?manage=users" class="btn butt my-2">Go back</a>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
    </div>
  </div>



<?php }; ?>




  <!-- MANAGE POSTS -->

  <?php if(isset($_GET["manage"]) && $_GET["manage"] == "posts") {
    if(!isset($_GET["search"])) {
      $posts = selectFromDB("SELECT * FROM posts ORDER BY dateposted DESC", true);
    } else {
      $q = mysqli_real_escape_string($conn, $_GET["search"]);
      $posts = selectFromDB("SELECT * FROM posts WHERE title LIKE '%$q%'  ORDER BY dateposted DESC", true);
    }
      ?>
    <div class="container">
      <?php if(count($posts) > 0) { ?>
      <div class="container">
        <div class="row">
            <div class="col-xs-6 col-lg-3" id="search">
              <form class="d-flex my-4" method="post" action="actions.php?searchPosts=true">
                <input name="searchPosts" class="form-control me-2 inputbg" type="search" placeholder="Search posts" aria-label="Search">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
              </form>
            </div>
        </div>

      <div class="row">
        <div class="col-12">
          <div class="container tbl tbody" id="table">
              <div class="row thead my-3 mx-3">
                <div class="col">Post ID</div>
                <div class="col">Title</div>
                <div class="col hideme">Original poster</div>
                <div class="col hideme">Replies</div>
                <div class="col hideme">Date posted</div>
                <div class="col hideme">Manage</div>
              </div>
            <div class="mb-3">
              <?php foreach($posts as $post) {
                $replies = getCount("SELECT COUNT(*) AS num FROM replies WHERE post_id = $post->id");
                $username = itemFromDB("SELECT username FROM users WHERE id='$post->user_id'", "username");
                 ?>
                 <hr>
              <div class="row my-3 mx-1">
                <div class="col"><?= $post->id ?></div>
                <div class="col truncate"><a href="post.php?id=<?= $post->id ?>" class="catlink"><?= $post->title ?></a></div>
                <div class="col hideme"><?= $username ?></div>
                <div class="col hideme"><?= $replies ?></div>
                <div class="col hideme"><?= $post->dateposted ?></div>
                <div class="col"><a href="actions.php?admindelete=post&postid=<?= $post->id ?>" class="catlink">Delete</a></div>
              </div>
            <?php } ?>
          </div>
          </div>
        </div>
      </div>
    </div>
  <?php } else { ?>
    <div class="container">
      <div class="container">
        <div class="card text-center cardbody">
          <div class="card-header cardhead">
            No results
          </div>
          <div class="card-body">
            <h5 class="card-title">No results containing "<?= $q ?>"</h5>
            <a href="dashboard.php?manage=posts" class="btn butt my-2">Go back</a>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>
  </div>





<?php }; }?>
