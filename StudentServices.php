<div align=right style="margin-right: 20px;"><a href="https://academic-php.cc.gatech.edu/groups/cs4400_Group4/LogOut.php">Logout</a></div><br>
<?php
	//Student services page
	include 'dbinfo.php';
		
	session_start();
	
	mysql_connect($dbhost, $dbusername, $dbpassword) or die("Unable to connect");
	mysql_select_db($database) or die ("Unable to select database");
?>

<a href="https://academic-php.cc.gatech.edu/groups/cs4400_Group4/RegisteredCourses.php">Register For Courses</a><br>
<a href="https://academic-php.cc.gatech.edu/groups/cs4400_Group4/PersonalStudInfo.php">Update Personal Information</a><br>
<a href="https://academic-php.cc.gatech.edu/groups/cs4400_Group4/FindTutors.php">Find Tutors</a><br>
<?php
	$user = $_SESSION['manager'];
    $query = "SELECT student_id FROM student WHERE username='" . $_SESSION['manager'] . "'";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    $query = "SELECT * FROM tutors_for where tutor_student_id = " . $row['student_id'];
	$result = mysql_query($query) or die(mysql_error());
	if(mysql_num_rows($result) != 0){
?>
<a href="https://academic-php.cc.gatech.edu/groups/cs4400_Group4/TutorLogbook.php">Tutor Logbook</a><br>
<?php
	}
?>
<a href="https://academic-php.cc.gatech.edu/groups/cs4400_Group4/StudentReport.php">View Grading Pattern</a><br>