<?php
	include_once 'mysql_connect.php';


	//connect to mysql database
	$conn = mysql_connect();

?>


<html>
	<head>
		<title>SQL Injection Project</title>
	</head>
	
<body>

<?php

if(!isset($_GET['install']) && !isset($_GET['clear']))
{

?>

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

<?php
}
else
{
	truncate_table($conn);

	echo "<div><a href='mysql.php'>Go back</a></div>";
}

?>

<div>

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



include_once 'unsanitized.php';

include_once 'sanitized.php';

$table_exists = check_table_exists($conn);

install_user_table($table_exists, $conn);

	
?>

</div>

<?php
if(!isset($_GET['install']))
{
?>

<div>


<?php
	if($table_exists === false)
	{
		echo "<div>
				If MySQL Table $table has been deleted, click <a href='mysql.php?install'>here</a>.
			  </div>";
	}
	else
	{


		echo "<p>Users in table:</p>

			  <div style='border:3px solid black;'>";

		//Select all users from table
		$query  = "SELECT * FROM `$table`";
		$result = $conn->query($query);
		$rows   = $result->num_rows;
	

		echo "<table border='1' width='50%'>
				<tr>
					<th>id</th>
					<th>Username</th>
				</tr>";


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
		
			$result->free_result();

			echo "<tr>
					<td colspan='2'>
						<a href='mysql.php?clear'>Clear table</a>
					</td>
				  </tr>";
		}
		else
		{

			echo "<tr>
					<td colspan='2'>No Users</td>
				  </tr>";

		}

		echo "</table>

			</div>";

	} //end scope of 'else' for $check_rows
?>
</div>

<?php
//end scope of !isset($_GET['install'])

}

	// Close the MySQLi connection
	$conn->close();
?>

<div>
	
	<table>
		<tr>
			<th>
			SQL Code to Inject
			</th>
		</tr>
		<tr>
			<td>
				test'), ('test2'), ('test3
			</td>
		</tr>
	</table>
</div>

</body>

</html>
