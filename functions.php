<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<?php 
session_start();

// connect to database
$db = mysqli_connect('localhost', 'root', '', 'pwnpatch');

// variable declaration
$username = "";
$email    = "";
$errors   = array(); 
function verify_task_solve($task,$user){
	global $db, $errors, $username, $email;
	$query = "SELECT * from solved_tasks where '$task'=task_id AND '$user'=user_id";
	$result = mysqli_query($db, $query);
	if ($result->num_rows > 0) {return false;}
	else{
		return true;
	}
}

// call the submitflag() function if submit_flag is clicked
if (isset($_POST['submit_flag'])) {
	submitflag();
}
function submitflag(){
	global $db, $errors, $username, $email;
	
	$task_id=e($_POST['task_id']);
	$flag=e($_POST['flag']);
	$query = "select flag,points from tasks where '$task_id'=id";
	$result = mysqli_query($db, $query);
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$new_points=$row['points'];
		$current_user= e($_SESSION['user']['id']);
		if ($row['flag']==$flag){
			
			if(verify_task_solve($task_id,$current_user)){
				$validate_task = "INSERT INTO  solved_tasks (task_id,user_id) VALUES('$task_id','$current_user') ;";
				mysqli_query($db, $validate_task);
			$add_query = "update users set total_points = total_points+'$new_points' where id = '$current_user' ";
			mysqli_query($db, $add_query);
			echo '<script><div class="alert alert-primary" role="alert">Correct</div></script>';
			
		}
		else{
		echo '<script> alert("You Already Solved the task");</script>';}
	}
	}
}
// call the add_tasks() function if add_task_btn is clicked
if (isset($_POST['add_task_btn'])) {
	add_tasks();
}
function add_tasks(){
	global $db, $errors, $username, $email;
	$name=e($_POST['name']);
	$description=e($_POST['task_description']);
	$points=e($_POST['points']);
	$flag=e($_POST['flag']);
	$query = "INSERT INTO tasks(name,description,points,flag) VALUES ('$name','$description','$points','$flag') ";
	mysqli_query($db, $query);

	}
	  


// call the assign_tasks() function if assign_tasks_btn is clicked
if (isset($_POST['assign_tasks_btn'])) {
	assign_tasks();
}


function assign_tasks(){
	global $db, $errors, $username, $email;
	$id=e($_POST['assigntasks_id']);
	$query = "DELETE from assigned_tasks where user_id='$id' ";
	mysqli_query($db, $query);
	foreach ($_POST as $key => $value) {
		if($value==1){
		$query = "INSERT INTO assigned_tasks(task_id, user_id) VALUES ((select id from tasks where name = '$key'),$id) ";
		mysqli_query($db, $query);
		}
	}
	  
}

// call the update_user() function if update_btn is clicked
if (isset($_POST['update_btn'])) {
	update_user();
}

function update_user(){
	global $db, $errors, $username, $email;
	$id=e($_POST['update_id']);
	$username = e($_POST['username']);
	$email = e($_POST['email']);
	$user_type =e($_POST['user_type']);
		// form validation: ensure that the form is correctly filled
		if (empty($username)) { 
			array_push($errors, "Username is required"); 
		}
		if (empty($email)) { 
			array_push($errors, "Email is required"); 
		}

	// register user if there are no errors in the form
	if (count($errors) == 0) {
		$query = "UPDATE users set username='$username',email='$email',user_type='$user_type' where id='$id' ";
			mysqli_query($db, $query);
			
			header('location: index.php');
			echo '<script> alert("User modified !");</script>';
		
	}
}

// call the register() function if register_btn is clicked
if (isset($_POST['register_btn'])) {
	register();
}

// REGISTER USER
function register(){
	// call these variables with the global keyword to make them available in function
	global $db, $errors, $username, $email;

	// receive all input values from the form. Call the e() function
    // defined below to escape form values
	$username    =  e($_POST['username']);
	$email       =  e($_POST['email']);
	$password_1  =  e($_POST['password_1']);
	$password_2  =  e($_POST['password_2']);

	// form validation: ensure that the form is correctly filled
	if (empty($username)) { 
		array_push($errors, "Username is required"); 
	}
	if (empty($email)) { 
		array_push($errors, "Email is required"); 
	}
	if (empty($password_1)) { 
		array_push($errors, "Password is required"); 
	}
	if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
	}

	// register user if there are no errors in the form
	if (count($errors) == 0) {
		$password = md5($password_1);//encrypt the password before saving in the database

		if (isset($_POST['user_type'])) {
			$user_type = e($_POST['user_type']);
			$query = "INSERT INTO users (username, email, user_type, password) 
					  VALUES('$username', '$email', '$user_type', '$password')";
			mysqli_query($db, $query);
			
			header('location: index.php');
			echo '<script> alert("User Added !");</script>';
		}else{
			$query = "INSERT INTO users (username, email, user_type, password) 
					  VALUES('$username', '$email', 'user', '$password')";
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
	$query = "SELECT * FROM users WHERE id=" . $id;
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
if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: login.php");
}

if (isset($_POST['login_btn'])) {
	login();
}

// LOGIN USER
function login(){
	global $db, $username, $errors;

	// grap form values
	$username = e($_POST['username']);
	$password = e($_POST['password']);

	// make sure form is filled properly
	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}

	// attempt login if no errors on form
	if (count($errors) == 0) {
		$password = md5($password);

		$query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
		$results = mysqli_query($db, $query);

		if (mysqli_num_rows($results) == 1) { // user found
			// check if user is admin or user
			$logged_in_user = mysqli_fetch_assoc($results);
			if ($logged_in_user['user_type'] == 'admin') {

				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "You are now logged in";
				header('location: admin/index.php');		  
			}else{
				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "You are now logged in";

				header('location: index.php');
			}
		}else {
			array_push($errors, "Wrong username/password combination");
		}
	}
}
function isAdmin()
{
	if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin' ) {
		return true;
	}else{
		return false;
	}
}

function isLoggedIn()
{
	if (isset($_SESSION['user'])) {
		return true;
	}else{
		return false;
	}
}

function getTasks(){
    global $db;

}

function listUsers(){
    global $db;
	$user=$_SESSION['user']['id'];
    $query = "SELECT * from users;";
    $result = mysqli_query($db, $query);

if ($result->num_rows > 0) {
  // output data of each row

  while($row = $result->fetch_assoc()) {
	echo $_SESSION['user']['username'];
    echo '<button class="btn open" onclick="open'.$row["username"].'()">Edit</button>';
}
} else {
  echo "there are no users";
}
}

?>