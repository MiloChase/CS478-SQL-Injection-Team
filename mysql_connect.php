<?php
require_once 'config.php';

function mysql_connect()
{
	$server = "localhost";
	$user	= "root";
	$pass	= "password";
	global $db;


	//Connect to the mysql server
	//$conn	= new mysqli($server,$user,$pass,$db);
	return new mysqli($server,$user,$pass,$db);
}

function check_table_exists($conn)
{
	global $table;

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

	return $table_exists;
}

function install_user_table($table_exists, $conn)
{
	global $table, $db;


	//Check if the webpage variable 'install' is set
	if(isset($_GET['install']))
	{
		//Check if the table is installed.
		if($table_exists === true)
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
}

function truncate_table($conn)
{
	global $table;
	
	$sql = "TRUNCATE TABLE `$table`";
	echo "SQL: $sql <br />";
	$query = $conn->query($sql);
}

?>