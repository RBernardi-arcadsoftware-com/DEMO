<html>
<?php
define("PAGE_TITLE", "Employee Update");
define("PHP_FILE_NAME", "employee_update.php")
?>

<title><?= PAGE_TITLE . " - " . PHP_FILE_NAME; ?></title>
<body>
<?php 

/* Set up connection for each pass through this application */
/* Scott was here again*/
$user='PHPUSER';
$password='PASSW0RD';
$db='<i5_system>';
$dbh = db2_connect($db, $user, $password);

if (!$dbh) :
die ("Problems connecting to the database.");
endif;

/* Get value of parameters which help determine the state of the application */
$action = $_POST["action"];
$customerNumber = $_GET["customerNumber"];

/* Determine which form to display based upon the state of the application */
if ($customerNumber == "") {
	if (!$action) {
		/* Display the initial Employee Search form and $_POST lastname field */
		/* when Search button is selected.                                    */
		print '<h1>Enter the first few characters of the employee records you wish to view/edit in the last name field.</h1>';
		print '<h2>For example, "JO":</h2>';
		print '<form action="employee_update.php" method="POST">';
		print 'Last Name: <input type="text" name="lastname" /> <br />';
		print '<input type="submit" name="action" value="Search" />';
		print '</form>';
	} else {
		/* Query file using input from form and deliver results to client */
		/* Construct the SQL statement, using lastname as the search substring */
		$sql = 'select * from i5schema.employee where lastname like \'' .$_POST["lastname"]. '%\'';
		/* Execute the DB2 SQL statement, place results into $stmt */
		$stmt = db2_exec($dbh, $sql, array('cursor' => DB2_SCROLLABLE));
		/* Print Employee Search Results header and table setup */
		print '<h1>Employee Records On System i starting with ' . $_POST["lastname"]. '</h1>';
		print '<br><table border=1 cellpadding=5 cellspacing=5>';
		/* Iterate through result set, printing one table line per record returned. */
		/* Note that the customerNumber field will be an "active field" which will  */
		/* $_GET the customerNumber and reinvoke the employee_update.php application*/
		while ($row = db2_fetch_array($stmt)) {
			if (!$row=="") {
				$customerNumber = $row[0];
				$customerName = $row[3];
				$customerFirst = $row[1];
				$workdept = $row[4];
				$job = $row[7];
				$salary = $row[11];
				print '<tr><td align=center><a href=employee_update.php?customerNumber=' . $customerNumber . '>' . $customerNumber .
				'</a><td>'.$customerName.'<td>'.$customerFirst.'<td>'.$workdept.'<td>'.$job.'<td>'.$salary.'</td></tr>';
			}
		}
		/* Close table */
		print '</table><br>';
		/* Print the DB2 SQL statement which was executed - Informational */
		print "<p>Echo of dynamically-built sql: ".$sql."</p>";
	}
} else { /* Edit the selected customer number record */
	/* Construct the SQL statement, using customerNumber to select the record */
	$sql = 'select * from i5schema.employee where empno = \'' .$_GET["customerNumber"]. '\'';
	/* Execute the DB2 SQL statement, place results into $stmt */
	$stmt = db2_exec($dbh, $sql, array('cursor' => DB2_SCROLLABLE));
	/* Place the field information returned into array $row */
	$row = db2_fetch_array($stmt);
	/* If there is data, move information from result array into fields */
	/* which will be used for screen display                            */
	if (!$row=="") {
		$customerNumber = $row[0];
		$customerName = $row[3];
		$customerFirst = $row[1];
		$workdept = $row[4];
		$job = $row[7];
		$salary = $row[11];
	}
	/* Display the Employee Edit form and $_GET all fields */
	/* when Update button is selected.                     */
	print '<h1>Edit an Employee record:</h1>';
	print '<form action="employee_update.php" method="GET">';
	print 'Customer Number: <input type="text" name="customerNumber" value="'.$customerNumber.'" /> <br />';
	print 'Last Name: <input type="text" name="customerName" value="'.$customerName.'" /> <br />';
	print 'First Name: <input type="text" name="customerFirst" value="'.$customerFirst.'"/> <br />';
	print 'Work Department: <input type="text" name="workdept" value="'.$workdept.'"/>';
	print 'Job Type: <input type="text" name="job" value="'.$job.'"/> <br />';
	print 'Salary: <input type="text" name="salary" value="'.$salary.'"/> <br />';
	print '<input type="submit" name="action" value="Update" /><a href=employee_update.php?customerNumber=' .
	$customerNumber .'&customerName=' .$customerName.'&customerFirst='.$customerFirst.'&workdept='.
	$workdept.'&job='.$job.'&salary='.$salary.'&action='.$submit.'></a>';
	print '</form>';
}
?>
	
</body>
</html>
