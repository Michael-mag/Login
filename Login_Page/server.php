<?php
	session_start();

	// variable declaration
	$username = "";
	$email    = "";
	$errors = array();
	$_SESSION['success'] = "";

	$host = "localhost";
	$dbusername = "thladi";
	$dbpassword = "3rZnWVDJ";
	$dbname = "udb_thladi";

	//create the connection
	$db = new mysqli ($host,$dbusername,$dbpassword,$dbname);
	// connect to database
	//$db = mysqli_connect('localhost', 'thladi', '3rZnWVDJ', 'udb_thladi');

	// REGISTER USER
	if (isset($_POST['reg_user'])) {
		// receive all input values from the form
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
		$password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

		// form validation: ensure that the form is correctly filled
		if (empty($username)) { array_push($errors, "Username is required"); }
		if (empty($email)) { array_push($errors, "Email is required"); }
		if (empty($password_1)) { array_push($errors, "Password is required"); }

		if ($password_1 != $password_2) {
			array_push($errors, "Passwords do not match");
		}

		//Check of user already exists or not and proceed accordingly
		$already_exists = " SELECT * FROM users WHERE email = '$email' ";
		$check_complete = mysqli_query($db,$already_exists);

		if(mysqli_num_rows($check_complete) > 0){
			array_push($errors, "User with this email already exists, login instead.");
		}

		// register user if there are no errors in the form
		echo "<br>";
		echo $password;
		if (count($errors) == 0) {
		$password = md5($password_1);//encrypt the password before saving in the database
		//$password = password_hash($password_1, PASSWORD_BCRYPT);
			$query = "INSERT INTO users (username, email, password)
					  VALUES('$username', '$email', '$password')";
			mysqli_query($db, $query);

			$_SESSION['username'] = $username;
			$_SESSION['success'] = "You are now logged in";
			header('location: index.php');
		}

	}

	// ...

	// LOGIN USER
	if (isset($_POST['login_user'])) {
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$password = mysqli_real_escape_string($db, $_POST['password']);

		if (empty($username)) {
			array_push($errors, "Username is required");
		}
		if (empty($password)) {
			array_push($errors, "Password is required");
		}

		if (count($errors) == 0) {
			$password = md5($password);
			$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
			$results = mysqli_query($db, $query);

			if (mysqli_num_rows($results) == 1) {
				$_SESSION['username'] = $username;
				$_SESSION['success'] = "You are now logged in";
				header('location: index.php');
			}else {
				array_push($errors, "Wrong username or password ");
			}
		}
	}

?>
