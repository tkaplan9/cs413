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
		</nav>
		<div class="content">
			<div>
				<?php if($_SESSION['position'] == ""): ?>
					<p>Since you do not have a registered position you cannot use this feature.</p>
				<?php else: ?>
					<p>Currently Working in: <?=$_SESSION['cname']?></p>
					<p>Current Job Title: <?=$_SESSION['position']?></p>
					<p>Current Salary: <?=number_format($_SESSION['salary'])?></p>			
					<p>Estimated Salary in Ankara Region: <?=number_format($row["AVG(salary)"])?></p>
					<p>Difference between Estimated and Current Salaries: <?=number_format($row["AVG(salary)"] - $_SESSION['salary'])?></p>
				<?php endif; ?>
			</div>
		</div>
	</body>
</html>