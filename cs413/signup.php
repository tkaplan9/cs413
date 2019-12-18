<?php
include 'config.php';
session_start();
$errors = array();
$flag=0;
// connect using credentials
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) { //connection error
	die ('Cannot connect to the database: ' . mysqli_connect_error());
}

if (isset($_POST['back'])) {
    header('location: index.html');
}
else if (isset($_POST['signedUser'])) {
    // receive all input values from the form
	$username = mysqli_real_escape_string($con, $_POST['name']);
	$city = mysqli_real_escape_string($con, $_POST['city']);
	$position = mysqli_real_escape_string($con, $_POST['position']);
	$salary = mysqli_real_escape_string($con, $_POST['salary']);
	$company = mysqli_real_escape_string($con, $_POST['company']);
	$password = mysqli_real_escape_string($con, $_POST['password']);
    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($username)) { array_push($errors, "Username is required"); }
    if (empty($password)) { array_push($errors, "Password is required"); }
    if (empty($city)) { array_push($errors, "City is required"); }
    if (empty($position) && empty($salary) && empty($company)) {$flag = 1;}
}

//first check the database to make sure 
// a user does not already exist with the same username
$user_check_query = "SELECT sname FROM employee WHERE sname='$username'";
$result = mysqli_query($con, $user_check_query);
if ( mysqli_num_rows($result) != 0 ) { // if user exists
    array_push($errors, "Username already exists");
}

// Finally, register user if there are no errors in the form
if (count($errors) == 0) {
    if($flag == 0){
        $query1 = "INSERT INTO employee (sname, password, scity, cname,position,salary) 
                    VALUES('$username', '$password', '$city', '$company', '$position', '$salary')";
		mysqli_query($con, $query1);
	}
	if($flag == 1){
		$query2 = "INSERT INTO employee (sname, password, scity) 
                    VALUES('$username', '$password', '$city')";
		mysqli_query($con, $query2);
	}
	alert("Your account has been created");
}
else //display errors if any
{
    $alertMsg = array_pop($errors);
    foreach( $errors as $counter ){
        $alertMsg .= ", " . array_pop($errors);
    }
    alert($alertMsg);
}

function alert($msg) {
    echo "<script type='text/javascript'>
        alert('$msg');
        window.location.href='index.html';
        </script>";
}
?>
