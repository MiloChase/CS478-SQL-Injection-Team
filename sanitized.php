<?php

//Check if "Sanitized Input" form was submitted
if(isset($_POST) && isset($_POST['submit_sanitized']))		
{

	//Using Whitelist approach
	//1. check if length is within bounds
	if(strlen($_POST['sanitize_text']) > $MAX_INPUT_LENGTH)
	{
		echo "Error: The input length was exceeded. <br />";
		echo "Cannot add user form <br />";
		echo "Text: ".$_POST['sanitize_text']."<br />";
		echo "Length: ".strlen($_POST['sanitize_text']);
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

		//3. Strip special characters
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $escape_string);
		echo "Stripped Special Characters: $string";

		//Check if user already exists in table before adding
		$check_user = "SELECT * FROM `$table` WHERE username='$string'";

		$check_query = $conn->query($check_user);
		$check_rows  = $check_query->num_rows;

		if($check_rows == 0)
		{
			$insert = "INSERT INTO `$table` (username) VALUES ('".$string."')";

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

?>