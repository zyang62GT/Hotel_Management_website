<div align=right style="margin-right: 20px;"><a href="https://academic-php.cc.gatech.edu/groups/cs4400_Group4/LogOut.php">Logout</a></div><br>
<?php
	//Tutor logbook
	include 'dbinfo.php';

	session_start();
	mysql_connect($dbhost, $dbusername, $dbpassword) or die("Unable to connect");
	mysql_select_db($database) or die ("Unable to select database");

	$username = $_SESSION['manager'];
	if(isset($_POST['submit'])) {
		//Gets student id from username
		$query = "SELECT Student_ID FROM student WHERE Username = '$username'";
		$result = mysql_query($query) or die(mysql_error());
		$row = mysql_fetch_assoc($result);
		$tutorStudentID = $row['Student_ID'];
		$CRN = $_POST['CRN'];
		$id = $_POST['id'];
		$dayTime = $_POST['DayTime'];
		//Adds log visit
		$query = "INSERT INTO logs_visit VALUES ('$tutorStudentID', '$id', '$CRN', '$dayTime')";
		$result = mysql_query($query) or die(mysql_error());
		header("Location: TutorLogbook.php");
	}
?>

<html>
	<body>
		<?php
			if(isset($_POST['getName'])) {
				$studentID = $_POST['id'];
				$code = $_POST['code'];
				//Get student id and crn
				$query = "SELECT student.Student_ID, sections.CRN FROM student INNER JOIN registers ON student.Student_ID = registers.Student_ID INNER JOIN sections ON sections.CRN = registers.CRN INNER JOIN course_code ON course_code.Course_Title = sections.Course_Title WHERE student.Student_ID = '$studentID' AND Code = '$code'";
				$result = mysql_query($query) or die(mysql_error());
				$row = mysql_fetch_assoc($result);
				if(mysql_num_rows($result) != 0) {
					echo "<p>Tutor Name: ".$username."</p>";
					echo "<form method=\"POST\" action=\"\"\"\">";
					echo "<input type=\"hidden\" name=\"CRN\" value=\"".$row['CRN']."\"/>";
					$dayTime = date('Y-m-d H:i:s');
					echo "<input type=\"hidden\" name=\"DayTime\" value=\"".$dayTime."\"/>";
					echo "<p>".$dayTime."</p>";
					echo "<select name=\"code\">";
					//Get course code
					$query = "SELECT Code FROM tutors_for INNER JOIN student ON student.Student_ID = tutors_for.Tutor_Student_ID INNER JOIN course_code ON course_code.Course_Title = tutors_for.Course_Title WHERE Username = '$username'";
					$result = mysql_query($query) or die(mysql_error());
					while($row = mysql_fetch_assoc($result)) {
						echo "<option>".$row['Code']."</option>";
					}
					echo "</select>";
					echo "<br/>";
					echo "Student ID: <input type=\"text\" name=\"id\" value=\"".$_POST['id']."\"/><input type=\"submit\" name=\"getName\" value=\"Generate Name\"/>";
					
					echo "<br/>";
					//Get name of student from id
					$query = "SELECT Name FROM student INNER JOIN regular_user ON student.Username = regular_user.Username WHERE Student_ID = '$studentID'";
					$result = mysql_query($query);
					$row = mysql_fetch_assoc($result);
					$name = $row['Name'];
					echo "<p>Student Name: ".$name."</p>";
					echo "<input type=\"submit\" name=\"submit\" value=\"Submit\"/>";
					echo "</form>";
				}
				else {
					die("Student is not currently enrolled for this class");
				}
			}
			else {
				echo "<p>Tutor Name: ".$username."</p>";
				echo "<form method=\"POST\" action=\"\"\"\">";
				echo "<select name=\"code\">";
				//Get course code
				$query = "SELECT Code FROM tutors_for INNER JOIN student ON student.Student_ID = tutors_for.Tutor_Student_ID INNER JOIN course_code ON course_code.Course_Title = tutors_for.Course_Title WHERE Username = '$username'";
				$result = mysql_query($query) or die(mysql_error());
				while($row = mysql_fetch_assoc($result)) {
					echo "<option>".$row['Code']."</option>";
				}
				echo "</select>";
				echo "<br/>";
				echo "Student ID: <input type=\"text\" name=\"id\"/><input type=\"submit\" name=\"getName\" value=\"Generate Name\"/>";
				
				echo "<br/>";
				echo "</form>";
			}
		?>
	</body>
</html>