<?php
session_start();
require_once("functions.php");
$conn = connectToDB();


//REGISTER
if(isset($_GET["form"]) && $_GET["form"] === "register") {
	$user = new stdClass();
	$_SESSION["message"] = [];
	if(isset($_POST["pass1"]) && isset($_POST["pass2"])) {
      if(trim($_POST["pass1"]) !== trim($_POST["pass2"])) {
			  array_push($_SESSION["message"], "Your passwords don't match.");
			  header("location:register.php");
      } else {
        $user->password = mysqli_real_escape_string($conn, md5(trim($_POST["pass1"])));
      }};
    if(isset($_POST["username"])) {
      if(usernameTaken($_POST["username"])) {
			  array_push($_SESSION["message"], "Username already taken.");
			  header("location:register.php");
      } else if (!preg_match('/^\w{5,}$/', $_POST["username"])) {
				array_push($_SESSION["message"], "Invalid username");
			  header("location:register.php");
			} else {
        $user->username = mysqli_real_escape_string($conn, trim($_POST["username"]));
      }};
    if (isset($_POST["email"]) && !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) == false) {
      if(emailExists(trim($_POST["email"]))) {
			  array_push($_SESSION["message"], "Email already registered");
			  header("location:register.php");
      } else {
        $user->email = mysqli_real_escape_string($conn, trim($_POST["email"]));
      }};

    if(isset($user->password) && isset($user->email) && isset($user->username)) {
      register($user);


	};
};



//LOG IN
if (isset($_GET["form"]) && $_GET["form"] === "login") {
		$user = new stdClass();
    if (isset($_POST["username"]) && isset($_POST["password"])) {
      $user->username = mysqli_real_escape_string($conn, trim($_POST["username"]));
      $user->password = mysqli_real_escape_string($conn, md5($_POST["password"]));
      if(correctUser($user)) {
        login($user);
      } else {
		$_SESSION["loginMessage"] = "Incorrect username and/or password.";
		header("location:register.php?login=true");
      };
    };
};



//NEW POST
if(isset($_GET["newpost"]) && $_GET["newpost"] == "true") {
	if(isset($_GET["cat"])) {
		$catID = $_GET["cat"];
		$userID = $_SESSION["userID"];
	$_SESSION["newPostMsg"] = [];
	if (isset($_POST["title"])) {
		$title = mysqli_real_escape_string($conn, htmlspecialchars($_POST["title"]));
	} else {
		array_push($_SESSION["newPostMsg"], "You haven't entered a title.");
	};
	if(isset($_POST["body"])) {
		$body = mysqli_real_escape_string($conn, htmlspecialchars($_POST["body"]));
	} else {
		array_push($_SESSION["newPostMsg"], "No post body.");
	};
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$target_dir = "images/";
		$target_file = $target_dir . basename($_FILES["image"]["name"]);

		move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

		$image = "images/" . $_FILES["image"]["name"];

		if($image === "images/") {
			$image = NULL;
		}
	};
	if (isset($_POST["title"]) && isset($_POST["body"])) {

			createPost($catID, $userID, $title, $body, $image);
		}
	}

};


//EDIT PROFILE
if(isset($_GET["editprofile"]) && $_GET["editprofile"] == 'true' && isset($_GET["user"])) {
	$_SESSION["editMsg"] = [];
	if(isset($_POST["newuser"])) {
		$userID = $_SESSION["userID"];
		if(($_POST["newuser"] == $_GET["user"] || !usernameTaken($_POST["newuser"])) && ($_POST["newuser"] != NULL) && ($_POST["newuser"] != " ")) {
		$newUsername = mysqli_real_escape_string($conn, trim($_POST["newuser"]));
	} else {
		array_push($_SESSION["editMsg"], "Username taken.");
		header("location:profile.php?user={$_GET['user']}&edit=true");
	}

	};
	if($_SERVER['REQUEST_METHOD']== 'POST') {
		$userID = $_SESSION["userID"];
		$originalimage = itemFromDB("SELECT avatar FROM users WHERE id='$userID'", 'avatar');


		$target_dir = "images/";
		$target_file = $target_dir . basename($_FILES["image"]["name"]);

		move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

		$image = "images/" . $_FILES["image"]["name"];

		if($image === "images/") {
			$image = $originalimage;
		}

		updateDB("UPDATE users SET avatar='$image' WHERE id='$userID'");



		if(isset($newUsername) && preg_match('/^\w{5,}$/', $newUsername)) {
				$newUser = mysqli_real_escape_string($conn, $newUsername);
				updateDB("UPDATE users SET username='$newUser' WHERE id='$userID'");
				header("location:profile.php?user=$newUsername");
				$_SESSION["loggedInUser"]->username = $newUser;
			};

			//header("location:profile.php?user={$_GET['user']}");

		}
};


if(isset($_GET["uploadavatar"]) && $_GET["uploadavatar"] == 'true' && isset($_GET["user"])) {
	if($_SERVER['REQUEST_METHOD']== 'POST') {
		$userID = $_SESSION["userID"];

		$target_dir = "images/";
		$target_file = $target_dir . basename($_FILES["image"]["name"]);

		move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

		$image = "images/" . $_FILES["image"]["name"];

		updateDB("UPDATE users SET avatar='$image' WHERE id='$userID'");
		header("location:profile.php?user={$_GET['user']}");

	};
	var_dump($_POST);
};


if(isset($_GET["reply"]) && $_GET["reply"] == 'true' && isset($_GET["post"])) {
	$userID = $_SESSION["userID"];
	$postID = $_GET["post"];
	$_SESSION["replyError"] =[];
	if($_GET["page"] == 0) {
		$thisPage = 1;
		} else {
		$thisPage = $_GET["page"];
	};
	if(isset($_POST["reply"]) && $_POST["reply"] !== "") {
		$body = mysqli_real_escape_string($conn, htmlspecialchars($_POST["reply"]));
		if($_SERVER["REQUEST_METHOD"] == "POST") {
			/*
			$target_dir = "images/";
			$target_file = $target_dir . basename($_FILES["image"]["name"]);

			move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

			$image = "images/" . $_FILES["image"]["name"];
			if($image === "images/") {
				$image = NULL;
			}
			*/

			$upload = $s3->putObject(array(
            'Bucket' => "phpforum",
            'Key'    => $_FILES['image']['name'],
            'SourceFile' => $_FILES['file']['tmp_name'],
        ));
			$image = "s3://phpforum/" . $_FILES["image"]["name"];
		};
	} else {
		array_push($_SESSION["replyError"], "Empty reply.");
		header("location:post.php?id=$postID");
	};
	if(isset($body) && count($_SESSION["replyError"]) == 0) {
		$_SESSION["replyError"] =[];
		reply($postID, $userID, $body, $image, $thisPage);
	}


};


if(isset($_GET["delete"]) && isset($_GET["id"])) {
	if($_GET["delete"] == "reply") {
		$id = $_GET["id"];
		$postID = itemFromDB("SELECT post_id FROM replies WHERE id='$id'", "post_id");
		deleteReply($id);

		header("location:post.php?id=$postID");
	};

	if($_GET["delete"] == "post") {
		$id = $_GET["id"];
		deletePost($id);
		header("location:index.php");
	};
};


if(isset($_GET["searchUsers"])) {
	$q = $_POST["searchUsers"];
	header("location:dashboard.php?manage=users&search=$q");
}

if(isset($_GET["searchPosts"])) {
	$q = $_POST["searchPosts"];
	header("location:dashboard.php?manage=posts&search=$q");
}


if(isset($_GET["search"]) && $_GET["search"] == "site") {
	$item = $_POST["searchSite"];
	header("location:search.php?item=$item");
}




if(isset($_GET["admindelete"])) {
	if($_GET["admindelete"] == "user" && isset($_GET["userid"])) {
		$userID = $_GET["userid"];
		deleteUser($userID);
		header("location:dashboard.php?manage=users");

	};

	if($_GET["admindelete"] == "post" && isset($_GET["postid"])) {
		$postID = $_GET["postid"];
		deletePost($postID);
		header("location:dashboard.php?manage=posts");
	}
};


if(isset($_GET["deleteaccount"]) && $_GET["deleteaccount"] == "true" && isset($_GET["id"])) {
	$id = $_GET["id"];
	deleteUser($id);
	session_destroy();
  header("location:index.php");
}






?>
