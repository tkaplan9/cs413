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

$sql = "SELECT AVG(salary) FROM employee WHERE position = '{$_SESSION['position']}'";
$data = "";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Income Estimator</title>
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
			<div>
				<p><b style="font-size:24px;"> </p>
				<p><br><b style="font-size:24px;">Currently Working in:<?=$_SESSION['cname']?></p>
				<p><b style="font-size:24px;">Current Job Title: <?=$_SESSION['position']?></p>
				<p><b style="font-size:24px;">Current Salary: <?=$_SESSION['salary']?></p>			
				<p><br><b style="font-size:24px;">Estimated Salary in Ankara Region: <?=$row["AVG(salary)"]?></b></p>
				<p><b style="font-size:24px;">Difference from Estimated and Current Salaries: <?=$row["AVG(salary)"]- $_SESSION['salary']?></b></p>
			</div>
		</nav>
		<div class="content">
		</div>
	</body>
</html>