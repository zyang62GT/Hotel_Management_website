<?php
	include 'dbinfo.php';

	session_start();
	$username = $_SESSION['manager'];

	mysql_connect($dbhost, $dbusername, $dbpassword) or die("Unable to connect");
	mysql_select_db($database) or die ("Unable to select database");

	$count = $_POST['count'];
	$coursesArray = array();
	//Get student id from username
	$query = "SELECT Student_ID FROM student WHERE Username = '$username'";
	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_assoc($result);
	$student_ID = $row['Student_ID'];

	for($i = 0; $i < $count; $i++) {
		if(isset($_POST['select'.$i])) {
			$crn = $_POST['CRN'.$i];
			//Check if registered courses have grades received
			$query = "SELECT * FROM registers INNER JOIN student ON registers.Student_ID = student.Student_ID WHERE Grade_Received IS NOT NULL AND username = '$username' AND CRN = '$crn'";
			$result = mysql_query($query) or die(mysql_error());
			if(mysql_num_rows($result) == 0) {
				$dataArray = array();
				array_push($dataArray, $_POST['CRN'.$i]);
				array_push($dataArray, $_POST['Code'.$i]);
				array_push($dataArray, $_POST['Name'.$i]);
				array_push($dataArray, $_POST['Letter'.$i]);
				array_push($dataArray, $_POST['Day'.$i]);
				array_push($dataArray, $_POST['Time'.$i]);
				array_push($dataArray, $_POST['Location'.$i]);
				array_push($dataArray, $_POST['mode'.$i]);
				array_push($coursesArray, $dataArray);
				$mode = $_POST['mode'.$i];
			
				$row = mysql_fetch_assoc($result);
				//Update registered courses
				$query = "INSERT INTO registers (Student_ID, CRN, Grade_Mode) VALUES ('$student_ID', '$crn', '$mode')";
				$result = mysql_query($query) or die(mysql_error());

			}
			else {
				die("You have already taken ".$_POST['CRN'].".");
			}
		}
	}

?>

<html>
	<body>
		<h1>Registered for the following courses:</h1>
		<table>
			<tr>
				<td>CRN</td>
				<td>Code</td>
				<td>Name</td>
				<td>Letter</td>
				<td>Day</td>
				<td>Time</td>
				<td>Location</td>
				<td>Mode</td>
		<?php
			for($i = 0; $i < count($coursesArray); $i++) {
				echo "<tr>";
				for($j = 0; $j < 8; $j++) {
					echo "<td>".$coursesArray[$i][$j]."</td>";
				}
				echo "</tr>";
			}
		?>
		</table>
		<a href="StudentServices.php"><button>Back to Student Services</button></a>
	</body>
</html>