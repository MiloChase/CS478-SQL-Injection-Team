<?php
global $conn;

//Check if "Unsanitized Input" form was submitted
if(isset($_POST) && isset($_POST['submit_unsanitized']))
{
	$unsanitized_input = $_POST['inject_text'];

	echo "<div style='border:1px solid black'>";
	echo "Unsanitized Input: <br />";	
	echo "String Length: ".strlen($unsanitized_input);
	

	$insert = "INSERT INTO `$table` (username) VALUES ('$unsanitized_input')";

	
	echo "Here is the input: $insert<br />";
	echo "</div>";

	$query = $conn->query($insert);

	if($query)
	{
		echo "<div>User $unsanitized_input added</div>";
	}
	else
	{
		echo "<div>Error: ".$query->error."</div>";
	}
}
?>