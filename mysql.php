<html>
	<head>
		<title>SQL Injection Project</title>
	</head>
	
<body>

<table>
	<tr>
		<td colspan='3' align='center'>
			Register User Example
		</td>
	</tr>
	<tr>
		<td>
			Username:
		</td>
		<td>
			<form method="POST" name="sanitized">
				<table>
					<tr>
						<th>Sanitized Input</th>
					</tr>
					
					<tr>
						<td>
							<input type='text' name='sanitize_text' />
						</td>
					</tr>
					<tr>
						<td align="right">
							<input type='submit' name='submit_sanitized' value='Register' />
						</td>
					</tr>
				</table>
			</form>
		</td>
		<td>
			<form method="POST" name="unsanitized">
				<table>
					<tr>
						<th>Unsanitized Input</th>
					</tr>
					
					<tr>
						<td>
							<input type='text' name='inject_text' />
						</td>
					</tr>
					<tr>
						<td align="right">
							<input type='submit' name='submit_unsanitized' value='Register' />
						</td>
					</tr>
				</table>
			</form>
		</td>
	</tr>

</table>

<div>

<?php
	$server = "localhost";
	$user	= "root";
	$pass	= "password";
	$db		= "sql_project";
	$table 	= "users";

	$MIN_INPUT_LENGTH = 5;
	$MAX_INPUT_LENGTH = 20;

	echo "<div>Input length must be at least $MIN_INPUT_LENGTH characters</div>";

	//Connect to the mysql server
	$conn	= new mysqli($server,$user,$pass,$db);
?>
	<div>
		MySQL Connection Status:
<?php

		if($conn->connect_errno)
		{
			echo "<div style='display:inline-block'>Connection error: ".$conn->connect_error."</div>";
			exit();
		}
		else
		{
			echo "<div style='color:green;display:inline-block'>Connected</div>";
		}
			
?>
	</div>


<?php

//Check if "Unsanitized Input" form was submitted
if(isset($_POST) && isset($_POST['submit_unsanitized']))
{
	echo "String Length: ".count($_POST['inject_text']);
}

//Check if "Sanitized Input" form was submitted
if(isset($_POST) && isset($_POST['submit_sanitized']))		
{
	//Using Whitelist approach
	//1. check if length is within bounds
	if(strlen($_POST['sanitize_text']) > $MAX_INPUT_LENGTH)
	{
		echo "Error: The input length was exceeded. <br />";
		echo "Cannot add user form";
	}
	else if(strlen($_POST['sanitize_text']) < $MIN_INPUT_LENGTH)
	{
		echo "Error: The input length must be at least $MIN_INPUT_LENGTH characters. <br />";
	}
	else
	{
		echo "<div style='border:1px solid;'>";
		//escape string
		echo "<br />Sanitized Form Sent! <br />";
		echo "Input Text: ".$_POST['sanitize_text']."<br />";
		echo "String Length: ".strlen($_POST['sanitize_text'])."<br />";

		//2. use mysql_escape_string($_POST[]); for user input
		$escape_string = $conn->real_escape_string($_POST['sanitize_text']);
		echo "Escaped String: $escape_string<br />";

		//Check if user already exists in table before adding
		$check_user = "SELECT * FROM `$table` WHERE username='$escape_string'";

		$check_query = $conn->query($check_user);
		$check_rows  = $check_query->num_rows;

		if($check_rows == 0)
		{
			$insert = "INSERT INTO `$table` (username) VALUES ('".$escape_string."')";

			$query = $conn->query($insert);

			if($query)
			{
				echo "<div>User $escape_string added</div>";
			}
			else
			{
				echo "<div>Error: ".$query->error."</div>";
			}
		}
		else
		{
			echo "<div>Username already exists in table.</div>";
		}

		echo "</div>";
	}

	
	
}


//This will check if the 'users' table is installed on the server
$check_table  = "SHOW TABLES";
$check_result = $conn->query($check_table);
$check_rows   = $check_result->num_rows;
if($check_rows > 0)
{
	$table_exists = false;
	for($i = 0; $i < $check_rows; $i++)
	{
		$fetch = $check_result->fetch_array();
		if($fetch[0] == $table)
		{
			$table_exists = true;
			break;
		}
	}

	if($table_exists === false)
	{
		echo "<div>Table `$table` does not exist</div>";
	}
}
else
{
	echo "<div>Table `$table` does not exist</div>";
}
//echo "Check Rows: $check_rows <br />";

//Check if the webpage variable 'install' is set
if(isset($_GET['install']))
{
	//Check if the table is installed.
	if($check_rows > 0)
	{
		echo "<div>User table already exists.</div>";
	}
	else
	{
		//Create table for users
		//This will run only if the table does not exist
		$create = "CREATE TABLE `$db`.`$table`(
				`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`username` varchar(256) NOT NULL
				);";

		$create_result = $conn->query($create);

		echo "<div>Creating table: $table</div>";
		echo "<div><a href='mysql.php'>Go Back</a></div>";

	}
}

	
?>

</div>

<?php
if(!isset($_GET['install']))
{
?>

<div>


<?php
	if($check_rows == 0)
	{
		echo "<div>
				If MySQL Table $table has been deleted, click <a href='mysql.php?install'>here</a>.
			  </div>";
	}
	else
	{
?>

		<p>Users in table:</p>

		<div style="border:3px solid black;">
<?php
		//check if table exists
		$query  = "SELECT * FROM `$table`";
		$result = $conn->query($query);
		$rows   = $result->num_rows;
	
?>
		<table border='1' width='50%'>
			<tr>
				<th>id</th>
				<th>Username</th>
			</tr>
<?php

		if($rows == 0)
		{
			echo "Rows: 0 <br />";	
		}
		else
		{
			echo "Rows: $rows<br />";
		}
		
		if($rows > 0)	
		{
		

			while($row = $result->fetch_row())
			{

				echo "<tr>";
				echo "<td>$row[0]</td>";
				echo "<td>$row[1]</td>";
				echo "</tr>";
				
			 	
			}
		
			$check->free_result();
		}
		else
		{
?>
			<tr>
				<td colspan="2">No Users</td>
			</tr>
<?php
		}
?>
		</table>
		</div>
<?php
	} //end scope of 'else' for $check_rows
?>
</div>

<?php
//end scope of !isset($_GET['install'])
}

	// Close the MySQLi connection
	$conn->close();
?>

</body>

</html>
