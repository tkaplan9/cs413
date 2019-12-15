<?php
include 'config.php';
session_start();

// connect using credentials
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) { //connection error
	die ('Cannot connect to the database: ' . mysqli_connect_error());
}
// check if both fields are filled
if ( $_POST['name'] == "" || $_POST['password'] == "" ) {
	alert("Please fill both fields");
}
// prepare sql stament
else if ($stmt = $con->prepare('SELECT sname, password, eid FROM employee WHERE sname = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	$stmt->bind_param('s', $_POST['name']);
	$stmt->execute();
	// check if account exists in our database
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($name, $password, $id);
        $stmt->fetch();
        // account exists, check the password.
        if ($_POST['password'] === $password) {
            // password exists too, log user in and create sessions so that we know in the other pages that the user is logged in
            // we assign the results we get from the database, not the one user entered into the text fields because of case insensivity
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $name;
            $_SESSION['id'] = $id;
            $_SESSION['password'] = $password;
            header('Location: home.php');
        } 
        else {
            alert("Password is incorrect");
        }
    } 
    else {
        alert("Username does not exist");
    }
    $stmt->close();
}


function alert($msg) {
    echo "<script type='text/javascript'>
        alert('$msg');
        window.location.href='index.html';
        </script>";
}
?>