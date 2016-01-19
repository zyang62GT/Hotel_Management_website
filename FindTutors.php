<div align=right style="margin-right: 20px;"><a href="https://academic-php.cc.gatech.edu/groups/cs4400_Group4/LogOut.php">Logout</a></div><br>
<?php
	//Search for tutors
	include 'dbinfo.php';
	session_start();
	$username = $_SESSION['manager'];

	mysql_connect($dbhost, $dbusername, $dbpassword) or die("Unable to connect");
	mysql_select_db($database) or die ("Unable to select database");
?>

<html>
	<body>
		<form method="POST" action="">
			Course Code:
			<input name="courseCode" type="text"/>
			OR Enter Keyword:
			<input name="keyword" type="text"/>
			<input type="submit" value="Search"/>
		</form>
		<table>
			<tr>
				<td>Course Code</td>
				<td>Course Name</td>
				<td>Tutor Name</td>
				<td>Tutor Email Address</td>
			</tr>
		<?php
			if(isset($_POST['courseCode'])) {
				$courseCode = $_POST['courseCode'];
				//Find course titles based on course code
				$query = "SELECT Course_Title FROM course_code WHERE Code = '$courseCode'";
				$result = mysql_query($query);
				if(mysql_num_rows($result) != 0) {
					$row = mysql_fetch_assoc($result);
					$courseTitle = $row['Course_Title'];
				}
				//Find list of names and emails of tutors based on course code
				$query = "SELECT Name, Email_ID FROM course_code INNER JOIN tutors_for ON tutors_for.Course_Title = course_code.Course_Title INNER JOIN student ON student.Student_ID = tutors_for.Tutor_Student_ID INNER JOIN regular_user ON regular_user.Username = student.Username WHERE Code = '$courseCode'";
				$result = mysql_query($query) or die(mysql_error());
				if(mysql_num_rows($result) != 0) {
					$tutorName = array();
					$tutorEmail = array();
					while($row = mysql_fetch_assoc($result)) {
						array_push($tutorName, $row['Name']);
						array_push($tutorEmail, $row['Email_ID']);
					}
					echo "<tr><td>".$courseCode."</td><td>".$courseTitle."</td><td>";
					for($i = 0; $i < mysql_num_rows($result); $i++) {
						echo "<p>".$tutorName[$i]."</p>";
					}
					echo "</td><td>";
					for($j = 0; $j < mysql_num_rows($result); $j++) {
						echo "<p>".$tutorEmail[$j]."</p>";
					}
					echo "</td></tr>";
				}
			}
			if(isset($_POST['keyword'])) {
				$courseKeyword = "%".$_POST['keyword']."%";
				//Find list of names and emails for tutors and course code
				$query = "SELECT Code, Name, Email_ID, course_code.Course_Title FROM course_code INNER JOIN tutors_for ON tutors_for.Course_Title = course_code.Course_Title INNER JOIN student ON student.Student_ID = tutors_for.Tutor_Student_ID INNER JOIN regular_user ON regular_user.Username = student.Username WHERE course_code.Course_Title LIKE '$courseKeyword'";
				$result = mysql_query($query) or die(mysql_error());
				if(mysql_num_rows($result)!= 0) {
					while($row = mysql_fetch_assoc($result)) {
						echo "<tr><td>".$row['Code']."</td><td>".$row['Course_Title']."</td><td>".$row['Name']."</td><td>".$row['Email_ID']."</td></tr>";
					}
				}
			}
		?>
		</table>
	</body>
</html>