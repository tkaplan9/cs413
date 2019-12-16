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

// message to display if redirected from apply page or submit_application
if (isset($_GET['Message'])) {
    print '<script type="text/javascript">alert("' . $_GET['Message'] . '");</script>';
}

// prepare sql statement
$data = "";
$sql = "SELECT cid, cname, position, quota, salary FROM (company NATURAL JOIN apply) WHERE eid = '{$_SESSION['id']}'";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
	$data .= "<table><tr><th width=\"20%\">Company Name</th>
	<th width=\"20%\">Position</th>
	<th width=\"20%\">Max Quota</th>
	<th width=\"20%\">Salary</th></tr>";
    while($row = mysqli_fetch_assoc($result)) {
		$data .= "<tr><td style=\"text-align:center\" width=\"20%\">" . $row["cname"]. 
		"</td><td style=\"text-align:center\" width=\"20%\">" . $row["position"]. 
		"</td><td style=\"text-align:center\" width=\"20%\">" . $row["quota"]. 
		"</td><td style=\"text-align:center\" width=\"20%\">" . $row["salary"]. 
		"</td><td style=\"text-align:center\" width =\"20%\"><a href=\"cancel_application.php?cid=" . $row["cid"] . "&position=" . $row["position"] . "\">Cancel Application</a></td></tr>";
    }
    $data .= "</table>";
} 
else {
    $data .= "You haven't applied to any company yet";
}

// prepare sql statement to display a single company 
$data2 = "";
$sql2 = "SELECT DISTINCT cid, t1.cname as cname, t1.position as position, t1.quota as quota, t1.salary as salary FROM
			( SELECT cid, cname, position, quota, salary FROM company 
			WHERE (cid, position) NOT IN 
				( SELECT cid, position FROM apply WHERE eid = '{$_SESSION['id']}' )
			) AS t1
			INNER JOIN
			( SELECT cid, cname, position, quota, salary FROM company c1 WHERE
			c1.quota > (SELECT COUNT(eid) FROM apply WHERE cid = c1.cid AND position = c1.position)
			) AS t2 
			USING(cid, position)";
$result2 = mysqli_query($con, $sql2);
if (mysqli_num_rows($result2) > 0) {
    // output data of each row
	$data2 .= "<table><tr><th width=\"15%\">Company Name</th>
	<th width=\"15%\">Position</th>
	<th width=\"15%\">Max Quota</th>
	<th width=\"15%\">Salary</th></tr>";
    while($row2 = mysqli_fetch_assoc($result2)) {
		$data2 .= "<tr><td style=\"text-align:center\" width=\"15%\">" . $row2["cname"]. 
		"</td><td style=\"text-align:center\" width=\"15%\">" . $row2["position"]. 
		"</td><td style=\"text-align:center\" width=\"15%\">" . $row2["quota"]. 
		"</td><td style=\"text-align:center\" width=\"15%\">" . $row2["salary"]. 
		"</td><td style=\"text-align:left\" width=\"20%\"><img src=\"images/" . $row2["cid"] . ".png\">".
		"</td><td style=\"text-align:center\" width=\"20%\"><a href=\"apply.php?cid=" . $row2["cid"] . "&position=" . $row2["position"] . "\">Apply to this position</a></td></tr>";
    }
    $data2 .= "</table>";
} 
else {
    $data2 .= "No available companies found";
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Welcome Page</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Jawbs</h1>
				<a href="home.php"><i class="fas fa-home"></i>Home</a>
				<a href="income_estimator.php"><i class="fas fa-search-dollar"></i>Income Estimator</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Welcome Page</h2>
			<form action="search.php" method="post">
				<input type="cname" name="cname" placeholder="Search a company" id="cname">
				<input type="image" src="images/search.png" value="Submit">
			</form>
			<p>Welcome, <?=$_SESSION['name']?></p>
			<div>
                <p><b style="font-size:24px;">Your current job status</b></p>
				<p>Working in: <?=$_SESSION['cname']?></p>
				<p>Job Title: <?=$_SESSION['position']?></p>
				<p>Current Salary: <?=$_SESSION['salary']?></p>
			</div>
            <div>
                <p><b style="font-size:24px;">Your applications</b></p>
                <?=$data?>
			</div>
			<div>
				<p><b style="font-size:24px;">Recommended openings for you</b></p>
                <?=$data2?>
            </div>
		</div>
	</body>
</html>