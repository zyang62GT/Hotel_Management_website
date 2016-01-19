<div align=right style="margin-right: 20px;"><a href="https://academic-php.cc.gatech.edu/groups/cs4400_Group4/LogOut.php">Logout</a></div><br>
<?php
	//Assign tutors
	include 'dbinfo.php';

	session_start();
	mysql_connect($dbhost, $dbusername, $dbpassword) or die("Unable to connect");
	mysql_select_db($database) or die ("Unable to select database");

	if(isset($_POST['newTutor'])) {
		$courseTitle = $_POST['courseTitle'];
		$student_Name = $_POST['newTutor'];
		//Get potential tutor id
		$query = "SELECT Student_ID FROM student INNER JOIN regular_user ON regular_user.Username = student.Username WHERE Name = '$student_Name'";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		$student_ID = $row['Student_ID'];
		//Assign tutor
		$query = "INSERT INTO tutors_for VALUES ('$student_ID', '$courseTitle')";
		$result = mysql_query($query) or die(mysql_error());

		//Delete from application list
		$query = "DELETE FROM application_for_tutoring WHERE Course_Title = '$courseTitle' AND Student_ID = '$student_ID'";
		$result = mysql_query($query) or die(mysql_error());
		header("Location TutorAssignment.php");
	}
	
?>

<html>
	<body>
		<form method="POST" action="">
		<select name="newTutor">
		<?php
			$username = $_SESSION['manager'];
			//Get possible names
			$query = "SELECT Name FROM faculty INNER JOIN sections ON faculty.Instructor_ID = sections.Instructor_ID INNER JOIN application_for_tutoring ON application_for_tutoring.Course_Title = sections.Course_Title INNER JOIN student ON student.Student_ID = application_for_tutoring.Student_ID INNER JOIN regular_user ON regular_user.Username = student.Username WHERE faculty.Username = '$username'";
			$result = mysql_query($query) or die(mysql_error());
			if(mysql_num_rows($result) != 0) {
				while($row = mysql_fetch_assoc($result)) {
					echo "<option>".$row['Name']."</option>";
				}
			}
			//Get course title
			$query = "SELECT Course_Title FROM faculty INNER JOIN sections ON sections.Instructor_ID = faculty.Instructor_ID WHERE Username = '$username'";
			$result = mysql_query($query);
			$row = mysql_fetch_assoc($result);
			$courseTitle = $row['Course_Title'];
			echo "<input type=\"hidden\" name =\"courseTitle\" value=\"".$courseTitle."\"/>";
		?>
		</select>
		<input type="submit" value=">>"/>
		</form>
		<table>
			<tr>
				<?php
					//Get tutor names
					$query = "SELECT Name FROM tutors_for INNER JOIN student ON student.Student_ID = tutors_for.Tutor_Student_ID INNER JOIN regular_user ON regular_user.Username = student.Username WHERE Course_Title = '$courseTitle'";
					$result = mysql_query($query) or die(mysql_error());
					if(mysql_num_rows($result) != 0) {
						while($row = mysql_fetch_assoc($result)) {
							echo "<td>".$row['Name']."</td>";
						}
					}
				?>
			</tr>
		</table>
	</body>
</html>

