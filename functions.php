<?php
require('vendor/autoload.php');

$s3 = new Aws\S3\S3Client([
    'version'  => 'latest',
    'region'   => 'eu-central-1',
]);

$bucket = getenv('S3_BUCKET');//?: die('No "S3_BUCKET" config var in found in env!');

function connectToDB() {
  // $ini = parse_ini_file("config.ini");

  $servername = getenv('HOST');
	$username = getenv('USERNAME');
	$password = getenv('PASSWORD');
	$dbname = getenv('DATABASE');

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
	if ($conn->connect_error) {
	  die("Connection failed: " . $conn->connect_error);
	}

	return $conn;
};


function register($user) {
  $conn = connectToDB();

  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);

  $username = $user->username;
  $email = $user->email;
  $password = $user->password;

  mysqli_stmt_execute($stmt);

  mysqli_stmt_close($stmt);

  $user->password = null;
  $_SESSION["loggedIn"]=true;
  $_SESSION["loggedInUser"] = $user;
  $_SESSION["userID"] = getUserID();
  $id = getUserID();
  $role = itemFromDB("SELECT role_id FROM users WHERE id='$id'", "role_id");
  $_SESSION["userRole"] = $role;
  header("location:index.php");
}

function login($user) {
  if (isset($user->password) && $user->password !== NULL) {
    $user->password = null;
  };
  $_SESSION["loggedIn"]=true;
  $_SESSION["loggedInUser"] = $user;
  $_SESSION["userID"] = getUserID();
  $id = getUserID();
  $role = itemFromDB("SELECT role_id FROM users WHERE id='$id'", "role_id");
  $_SESSION["userRole"] = $role;
  header("location:index.php");
}

function logout() {
  session_destroy();
  header("location:index.php");
};





function selectFromDb($sql, $asObject) {
		$conn = connectToDB();


		$result = $conn->query($sql);
		$tempArray = [];

		if ($result->num_rows > 0) {
			if ($asObject === true) {
				while($row = $result->fetch_object()) {
					array_push($tempArray,$row);
				}
			} else {
				while($row = $result->fetch_assoc()) {
					array_push($tempArray,$row);
				}
			}
		}
		return $tempArray;
	};


function isLoggedIn() {
		if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
			return true;
		} else {
		return false;
		};
	};



function emailExists($email) {
  $usersEmails = selectFromDB("SELECT email FROM users", false);
  $temp = [];
  foreach ($usersEmails as $thisemail) {
    array_push($temp, $thisemail["email"]);
  };

  if(in_array($email, $temp)) {
    return true;
  } else {
    return false;
  }
};

function usernameTaken($username) {
  $usernames = selectFromDB("SELECT username FROM users", false);

  $temp = [];
  foreach ($usernames as $thisusername) {
    array_push($temp, $thisusername["username"]);
  };
  if (in_array($username, $temp)) {
    return true;
  } else {
    return false;
  }
};


function errorAlert($msg) {
  echo '<div class="container"> <div class="alert alert-danger disappear" role="alert">
  '. $msg .'
</div> </div>';
}

function correctUser($user) {
  $conn = connectToDB();
  if(isset($user->username) && isset($user->password)) {
    $sql = "SELECT * FROM users WHERE username=? AND password=?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);

    $username = $user->username;
    $password = $user->password;

    $userLoggedIn = mysqli_stmt_execute($stmt);


    if ($userLoggedIn) {
      return TRUE;
    } else {
      return FALSE;
    };
  };
};


function getUserID() {
  $username = $_SESSION["loggedInUser"]->username;
  $temp = selectFromDB("SELECT * FROM users WHERE username='$username'", true);
  return $temp[0]->id;
}



function createPost($catID, $userID, $title, $body, $image) {
  $conn = connectToDB();
  $sql = "INSERT INTO posts (category_id, user_id, title, body, file) VALUES (?, ?, ?, ?, ?)";

  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "iisss", $catID, $userID, $title, $body, $image);

  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);


  $latest = selectFromDB("SELECT id FROM posts Order By id Desc", true);
  $id = $latest[0]->id;
  header("location:post.php?id=$id");
};



function itemFromDB($sql, $item) {
  $temp = selectFromDB($sql, true);
  return $temp[0]->$item;
};

function getPostCount($item, $table) {
  $sql = "SELECT COUNT($item) AS num FROM $table WHERE category_id='$item'";
  $temp = selectFromDB($sql, true);
  return $temp[0]->num;
};

function getReplyCount($item, $table) {
  $sql = "SELECT COUNT($item) AS num FROM $table WHERE post_id='$item'";
  $temp = selectFromDB($sql, true);
  return $temp[0]->num;
};

function getCount($sql) {
  $temp = selectFromDB($sql, true);
  return $temp[0]->num;
}


function updateDB($sql) {
  $conn = connectToDB();

		if ($conn->query($sql) === TRUE) {
		  echo "New record updated successfully";
		} else {
		  echo "Error: " . $sql . "<br>" . $conn->error;
		}

		$conn->close();
};

function reply($postID, $userID, $body, $image, $page) {
  $conn = connectToDB();
  $sql = "INSERT INTO replies (post_id, user_id, body, file) VALUES (?, ?, ?, ?)";

  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "iiss", $postID, $userID, $body, $image);

  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);


  $latest = selectFromDB("SELECT id FROM replies WHERE post_id='$postID' Order By id Desc", true);
  $id = $latest[0]->id;

  header("location:post.php?id=$postID&page=$page#$id");
};


function deletePost($id) {
  $conn = connectToDB();

  $sql = "DELETE FROM posts WHERE id='$id'";

  if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
  } else {
    echo "Error deleting record: " . $conn->error;
  };


  $sqlreplies = "DELETE FROM replies WHERE post_id='$id'";

  if ($conn->query($sqlreplies) === TRUE) {
    echo "Record deleted successfully";
  } else {
    echo "Error deleting record: " . $conn->error;
  };


  };


function deleteReply($id) {

  $conn = connectToDB();

  $sql = "DELETE FROM replies WHERE id='$id'";

  if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
  } else {
    echo "Error deleting record: " . $conn->error;
  };



  };


  function deleteUser($id) {

    $conn = connectToDB();

    $sql = "DELETE FROM users WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
      echo "Record deleted successfully";
    } else {
      echo "Error deleting record: " . $conn->error;
    };
  }




?>
