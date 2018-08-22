<?php 
session_start();

// connect to database
$db = mysqli_connect('localhost', 'root', '', 'admin');

// variable declaration
$user_name = "";
$user_type = "";
$user_status = "";
$user_email    = "";
$errors   = array(); 

// call the register() function if register_btn is clicked
if (isset($_POST['register_btn'])) {
	register();
}

// REGISTER USER
function register(){
	// call these variables with the global keyword to make them available in function
	global $db, $errors, $user_name, $user_email;

	// receive all input values from the form. Call the e() function
    // defined below to escape form values
	$user_name    =  e($_POST['user_name']);
	$user_type    =  e($_POST['user_type']);
	$user_status    =  e($_POST['user_status']);
	$user_email       =  e($_POST['user_email']);
	$user_password_1  =  e($_POST['user_password_1']);
	$user_password_2  =  e($_POST['user_password_2']);

	// form validation: ensure that the form is correctly filled
	if (empty($user_name)) { 
		array_push($errors, "Username is required"); 
	}
	if (empty($user_email)) { 
		array_push($errors, "Email is required"); 
	}
	if (empty($user_password_1)) { 
		array_push($errors, "Password is required"); 
	}
	if ($user_password_1 != $user_password_2) {
		array_push($errors, "The two user_passwords do not match");
	}

	// register user if there are no errors in the form
	if (count($errors) == 0) {
		$user_password = md5($user_password_1);//encrypt the user_password before saving in the database

		if (isset($_POST['user_type'])) {
			$user_type = e($_POST['user_type']);
			$query = "INSERT INTO user_details (user_name, user_email, user_type,user_status, user_password) 
					  VALUES('$user_name', '$user_email', '$user_type',$user_status, '$user_password')";
			mysqli_query($db, $query);
			$_SESSION['success']  = "New user successfully created!!";
			header('location: login.php');
		}else{
			$query = "INSERT INTO user_details (user_name, user_email, user_type,user_status user_password) 
					  VALUES('$user_name', '$user_email', '$user_type',$user_status '$user_password')";
			mysqli_query($db, $query);

			// get id of the created user
			$logged_in_user_id = mysqli_insert_id($db);

			$_SESSION['user'] = getUserById($logged_in_user_id); // put logged in user in session
			$_SESSION['success']  = "You are now logged in";
			header('location: index.php');				
		}
	}
}

// return user array from their id
function getUserById($id){
	global $db;
	$query = "SELECT * FROM user_details WHERE id=" . $id;
	$result = mysqli_query($db, $query);

	$user = mysqli_fetch_assoc($result);
	return $user;
}

// escape string
function e($val){
	global $db;
	return mysqli_real_escape_string($db, trim($val));
}

function display_error() {
	global $errors;

	if (count($errors) > 0){
		echo '<div class="error">';
			foreach ($errors as $error){
				echo $error .'<br>';
			}
		echo '</div>';
	}
}	