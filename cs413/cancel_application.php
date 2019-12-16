<?php
include 'config.php';
session_start();

// connect using credentials
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) { //connection error
	die ('Cannot connect to the database: ' . mysqli_connect_error());
}

//get cid and position
$cid = "";
$position = "";
if (isset($_GET['cid']) && isset($_GET['position']) ) {
	$cid = $_GET['cid'];
	$position = $_GET['position'];
}

$deleteSQL = "DELETE FROM apply WHERE eid = '{$_SESSION['id']}' AND cid = '$cid' AND position = '$position'";
if(mysqli_query($con, $deleteSQL))
{
    header("Location: home.php?Message=" . urlencode("Cancellation successful"));
}
else {
    header("Location: apply.php?Message=" . urlencode("Cancellation NOT successful"));
}
?>