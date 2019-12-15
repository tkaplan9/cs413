<?php
include 'config.php';
session_start();

// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit();
}

// connect using credentials
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	die ('Cannot connect to the database: ' . mysqli_connect_error());
}

// prepare sql stament
$data = "";
$sql = "SELECT cid, cname, quota FROM company WHERE cname = '{$_POST['cname']}'";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {
	// output data of each row
	$data .= "<table><tr><th width=\"25%\">Company Name</th><th width =\"25%\">Max Quota</th><th width=\"25%\"></th></tr>";
	while($row = mysqli_fetch_assoc($result)) {
		$data .= "<tr><td style=\"text-align:center\" width=\"25%\">" . $row["cname"]. 
		"</td><td style=\"text-align:center\" width=\"25%\">" . $row["quota"]. 
		"</td><td style=\"text-align:left\" width=\"25%\"><img src=\"images/" . $row["cid"] . ".png\">".
		"</td><td style=\"text-align:center\" width=\"25%\"><a href=\"apply.php?cid=" . $row["cid"] . "\">Apply to this company</a></td></tr>";
	}
	$data .= "</table>";
}
else{
	$data .= "No available companies found";
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Search Results</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Jawbs</h1>
				<a href="home.php"><i class="fas fa-arrow-alt-circle-left"></i>Back</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Search Results</h2>
			<div>
                <?=$data?>
			</div>
		</div>
	</body>
</html>