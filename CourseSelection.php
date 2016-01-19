<?php
	include 'dbinfo.php';
	//Course selection
	session_start();
	$username = $_SESSION['manager'];

	mysql_connect($dbhost, $dbusername, $dbpassword) or die("Unable to connect");
	mysql_select_db($database) or die ("Unable to select database");

	//Get student's major
	$query = "SELECT Major FROM student WHERE username = '$username'";
	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_assoc($result);
	$department = $row['Major'];
	$term = "Fall 2012";

	//Get all courses offered this term for department
	$query = "SELECT * FROM department INNER JOIN offers ON department.Dept_ID = offers.Dept_ID INNER JOIN sections ON offers.Course_Title = sections.Course_Title INNER JOIN course_code ON offers.Course_Title = course_code.Course_Title INNER JOIN faculty ON sections.Instructor_ID = faculty.Instructor_ID INNER JOIN regular_user ON faculty.Username = regular_user.Username WHERE Dept_Name = '$department' AND Term = '$term'";
	$result = mysql_query($query) or die(mysql_error());
	echo $query;

?>

<html>
	<head>
	</head>
	<body>
		<?php
			echo "<p>Term: ".$term."</p>";
			echo "<p>Department: ".$department."</p>";
		?>
		<table>
			<tr>
				<td>Select</td>
				<td>CRN</td>
				<td>Course Code</td>
				<td>Section</td>
				<td>Instructor</td>
				<td>Days</td>
				<td>Time</td>
				<td>Location</td>
				<td>Mode of Grading</td>
			</tr>
		<form action="RegisteredCourses.php" method="POST">
				<?php
					$result = mysql_query($query) or die(mysql_error());
					for($i = 0; $i < mysql_num_rows($result); $i++) {
						$row = mysql_fetch_assoc($result);
						echo "<tr><td><input type=\"checkbox\" name=\"select".$i."\"/></td><td>".$row['CRN']."</td><td>".$row['Code']."</td><td>".$row['Letter']."</td><td>".$row['Name']."</td><td>".$row['Day']."</td><td>".$row['Time']."</td><td>".$row['Location']."</td><td><select name=\"mode".$i."\"><option>Register</option><option>Audit</option><option>Pass/Fail</option></select></td></tr>";
						echo "<input type=\"hidden\" name=\"CRN".$i."\" value=\"".$row['CRN']."\"/><input type=\"hidden\" name=\"Code".$i."\" value=\"".$row['Code']."\"/><input type=\"hidden\" name=\"Letter".$i."\" value=\"".$row['Letter']."\"/><input type=\"hidden\" name=\"Name".$i."\" value=\"".$row['Name']."\"/><input type=\"hidden\" name=\"Day".$i."\" value=\"".$row['Day']."\"/><input type=\"hidden\" name=\"Time".$i."\" value=\"".$row['Time']."\"/><input type=\"hidden\" name=\"Location".$i."\" value=\"".$row['Location']."\"/>";
					}
					echo "<input type=\"hidden\" name=\"count\" value=\"".mysql_num_rows($result)."\"/>";
				?>
		</table>
				<input type="submit" value="Register"/>
		</form>
	</body>
</html>