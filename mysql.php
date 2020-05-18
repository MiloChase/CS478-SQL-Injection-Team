<html>
	<head>
		<title>SQL Injection Project</title>
	</head>
	
<body>

<?php
	$server = "localhost";
	$user	= "root";
	$pass	= "password";
	$db	= "sql_project";

	$conn	= new mysqli($server,$user,$pass,$db);

	if($conn->connect_error)
		echo "Connection error: ".$conn->connect_error;
	else
		echo "Connection is created successfully";
		

	if(isset($_POST) && isset($_POST['submit_form']))
	{
		echo "<br />Form Sent! <br />";
		echo "Input Text: ".$_POST['inject_text'];
	}

	//check if table exists
	$query  = "SELECT * FROM `$db`.`users`";
	$check  = $conn->query($query);

	echo "<br />Number of users: ";
	
	if($check > 0)	
	{
		echo $check."<br />";
		$check->free_result();
	}
	else
	{
		echo "No users <br />";
	}
	
/*
	//Create table for users
	$create = "CREATE TABLE `$db`.`users`(
			`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`username` varchar(256) NOT NULL
			);";
	
	
	
	$insert = "INSERT INTO `$db`.";
*/	
	// Close the MySQLi connection
	$conn->close();
	
?>

<form method="POST" name="inject">

	<table>
		<tr>
			<th>Injection SQL</th>
		</tr>
		
		<tr>
			<td>
				<input type='text' name='inject_text' />
			</td>
		</tr>
		<tr>
			<td>
				<input type='Register' name='submit_form' value='submit_form' />
			</td>
		</tr>
	</table>

</form>

</body>

</html>
