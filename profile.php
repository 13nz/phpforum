<?php
$pageTitle = $username = $_GET["user"] . " - Profile";
include("header.php");
if(isLoggedIn()) {
  $id = $_SESSION["userID"];
};
if(isset($_GET["user"])) {
$username = $_GET["user"];
?>


<div class="container">
  <div class="row">
    <div class="col-12" style="height: 8vh;">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page"><a href="index.php" class="link-secondary breadlink" style="text-decoration: none">Home</a></li>
        </ol>
      </nav>
    </div>
  </div>
</div>


<?php
if(!usernameTaken($username)) { ?>
  <div class="container">
    <div class="container">
      <div class="card text-center cardbody">
        <div class="card-header cardhead">
          Oops
        </div>
        <div class="card-body">
          <h5 class="card-title">This user doesn't exist</h5>
          <a href="index.php" class="btn butt">Home</a>
        </div>
      </div>
    </div>
  </div>
<?php } else {
  $thisID = itemFromDB("SELECT id FROM users WHERE username='$username'", "id");
  $pic = itemFromDB("SELECT avatar FROM users WHERE id='$thisID'", "avatar");



if(!isLoggedIn()) { ?>
  <div class="container">
    <div class="container">
      <div class="card text-center cardbody">
        <div class="card-header cardhead">
          Restricted
        </div>
        <div class="card-body">
          <h5 class="card-title">Cannot access</h5>
          <p class="card-text">You need to be logged in to view this page.</p>
          <a href="register.php?login=true" class="btn butt">Log in</a>
        </div>
      </div>
    </div>
  </div>



<?php } else {


if(isset($_GET["user"])) { ?>




  <div class="container">
    <?php
    if(!isset($_GET["edit"])) {
      //CHECK IF USER LOGGED IN
      if($id === $thisID) {
      ?>



    <div class="row justify-content-end">
      <div class="col-3">
        <form>
          <button type="button" class="btn btn-outline-secondary mb-1"><a class="butlink" href="profile.php?user=<?= $_GET["user"] ?>&edit=true">Edit</a></button>
        </form>
      </div>
    </div>


  <?php } ?>

    <div class="row">
      <div class=" col-xs-12 col-sm-12 col-md-4 col-lg-2">
        <img src="<?= $pic ?>" class="img-fluid avi">
      </div>
      <div class=" col-xs-12 col-sm-6 col-md-6 col-lg-4">
        <form>
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" value="<?= $_GET['user']; ?>" class="form-control inputbg" placeholder="<?= $_GET['user']; ?>" disabled>
            </div>
        </form>
      </div>
    </div>

    <?php
      $userPosts = selectFromDB("SELECT * FROM posts WHERE user_id='$thisID' ORDER BY dateposted DESC LIMIT 5", true);
      $userReplies = selectFromDB("SELECT * FROM replies WHERE user_id='$thisID' ORDER BY datereplied DESC LIMIT 5", true);

     ?>

    <div class="row my-5">
      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 my-3">
        <div class="container tbody tbl" id="table">
          <?php if(count($userPosts) > 0) { ?>
            <div class="row thead my-3 mx-1">
              <div class="col"><b>Latest posts</b></div>
              <div class="col hideme"></div>
            </div>
            <div>
          <?php foreach($userPosts as $post) {
            $category = itemFromDB("SELECT name FROM categories WHERE id='$post->category_id'", 'name');
            ?>
          <hr>
            <div class="row my-3 mx-1">
              <div class="col truncate"><a href="post.php?id=<?= $post->id ?>" class="catlink tablink"><?= $post->title ?></a></div>
              <div class="col hideme"><a href="category.php?id=<?= $post->category_id ?>" class="catlink"><?= $category ?></a></div>
            </div>

        <?php } ?>
        </div>
      </div>
      </div>
    <?php } ?>
    <?php if(count($userReplies) > 0) {
      ?>
      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 my-3">
        <div class="container tbody tbl" id="table">
            <div class="row thead my-3 mx-1">
              <div class="col"><b>Latest replies</b></div>
              <div class="col hideme"></div>
            </div>
            <div>
          <?php forEach($userReplies as $reply) {
            $postTitle = itemFromDB("SELECT title FROM posts WHERE id='$reply->post_id'", "title");
             ?>

            <hr>
            <div class="row my-3 mx-1">
              <div class="col truncate"><a href="post.php?id=<?= $reply->post_id ?>#<?= $reply->id ?>" class="catlink tablink"><?= $reply->body ?></a></div>
              <div class="col hideme truncate"><a href="post.php?id=<?= $reply->post_id ?>" class="catlink tablink"> <?= $postTitle ?></a></div>
            </div>

        <?php } ?>
        </div>
      </div>
      </div>
    <?php } ?>
    </div>





  <?php }
    if(isset($_GET["edit"]) && $_GET["edit"] == 'true') { ?>


      <form method="POST" action="actions.php?editprofile=true&user=<?=$_GET['user'] ?>" enctype="multipart/form-data">
      <div class="row justify-content-end">
        <div class="col-3">

            <button type="submit" class="btn btn-outline-secondary mb-1">Save</button>

        </div>
      </div>


      <div class="row">
        <div class=" col-xs-12 col-sm-12 col-md-4 col-lg-2">
          <img src="<?= $pic ?>" class="img-fluid avi">
        </div>
        <div class=" col-xs-12 col-sm-6 col-md-6 col-lg-4">
              <div class="mb-3">
                <label class="form-label">Edit username</label>
                <input name="newuser" type="text" value="<?= $_SESSION['loggedInUser']->username; ?>" class="form-control inputbg" placeholder="<?= $_SESSION['loggedInUser']->username; ?>">
              </div>
            <form method="POST" action="actions.php?uploadavatar=true&user=<?=$_GET['user'] ?>" enctype="multipart/form-data">
              <div class="form-group my-3">
                <label class="form-label">Change avatar</label>
                <br>
                <input name="image" type="file" class="form-control-file">
              </div>
          </form>
          <form method="POST" action="actions.php?deleteaccount=true&id=<?= $id ?>">
          <div class="row justify-content-start">
            <div class="col-6">

                <button type="submit" class="btn btn-secondary btn-sm mb-1">Delete account</button>

            </div>
          </div>
          <div class="my-5">
            <?php
            if (isset($_SESSION["editMsg"]) && isset($_SESSION["editMsg"][0])) {
            	echo errorAlert($_SESSION["editMsg"][0]);
            };
            };
            ?>
          </div>
        </div>

      </div>
    </div>








  <?php }
} }}?>

  </div>





<?php include("footer.php"); ?>
