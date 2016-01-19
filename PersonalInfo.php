<?php
  include 'dbinfo.php'
?>

<html>
<head>
</head>
<body>

<?php
	session_start();

	$username = $_SESSION['manager'];
	if(isset($_POST['Submit']))
	{
		//UPDATE ALL THE THINGS
		$query1 = "UPDATE student SET major='" . $_POST['majors'] . "', degree='" . $_POST['degree'] . "', tutor='" . $_POST['tutor'] . "' where username='$username'";
		$mysqldate = $_POST['dob'];
		$phpdate = strtotime($mysqldate);
		$mysqldate = date('Y-m-d H:i:s', $phpdate);
		$query2 = "UPDATE regular_user SET Date_Of_Birth=" . $mysqldate . ", Address='" . $_POST['address'] . "', Permanent_Address='" . $_POST['perm_address'] . "', Gender='" . $_POST['gender'] . ", Contact_No=" . $_POST['contact'];
		mysql_query($query1) or die(mysql_error());
		mysql_query($query2) or die(mysql_error());
		header("Location: LoggedIn.php");
	}
	elseif(isset($_POST['tutor'])){
		$query = "INSERT INTO application_for_tutoring VALUES(" . $_SESSION['id'] . ", " . $_POST['tutor_courses'] . ")";
		
		mysql_query($query) or die(mysql_error());
	}
	elseif(isset($_POST['edu'])){
		$query = "INSERT INTO education_history VALUES(" . $_SESSION['id'] . ", " . $_POST['pinst'] .", " . $_POST['pgrad'] . ", " . $_POST['pdegree'] . ", " . $_POST['pmajor'] . ", " . $_POST['pgpa'] . ")"; 
	}
	mysql_connect($dbhost, $dbusername, $dbpassword) or die("Unable to connect");
	mysql_select_db($database) or die ("Unable to select database");

	$sql_query = "SELECT * FROM student NATURAL JOIN regular_user WHERE username = '$username'";
	$result = mysql_query($sql_query) or die(mysql_error());

	if(mysql_num_rows($result) == 1){
		$row = mysql_fetch_assoc($result);
		$name = $row['Name'];
		$address = $row['Address'];
		$perm_address = $row['Permanent_Address'];
		$email = $row['Email_ID'];
		$dob = $row['Date_Of_Birth'];
		$gender = $row['Gender'];
		$contact = $row['Contact_No'];
		$tutor = $row['Tutor'];
		$degree = $row['Degree'];
		$major = $row['Major'];
		$id = $row['Student_ID'];
		$_SESSION['id'] = $id;
?>
<form action = "" method = "POST" >
Name: 
<input name="name" size=20 maxlength=45 value = '<?php echo $name; ?>'/>
<br>
Date of Birth: 
<input name="dob" size=20 maxlength=45 value = '<?php echo $dob; ?>'/>
<br>
Gender:
<select name="gender">
<?php
  if(strcmp(strtolower($gender), "male") == 0){
?>
<option value="male" selected="selected">Male</option>
<option value="female">Female</option>
<?php
  }
  else{
?>
<option value="Male">Male</option>
<option value="Female" selected="selected">Female</option>
<?php
  }
?> 
</select>
<br>
Address: 
<input name="address" size=20 maxlength=100 value = '<?php echo $address; ?>'/>
<br>
Permament Address: 
<input name="perm_address" size=20 maxlength = 100 value = '<?php echo $perm_address; ?>'/>
<br>
Contact Number: 
<input name="contact" size=11 maxlength=11 value='<?php echo $contact; ?>'/>
<br>
Email Address: 
<input name="email" size=20 maxlength=50 value='<?php echo $email; ?>'/>
<br>
Willing to tutor? 
<?php 
	if($tutor == 1){
?>
<input type="radio" name="tutor" value="1" checked="checked"/> Yes
<input type="radio" name="tutor" value="0"/> No
<?php	} 
	else{
?>
<input type="radio" name="tutor" value="1"/> Yes
<input type="radio" name="tutor" value="0" checked="checked"/> No
<?php }
?>
<br>
<br>
If Yes, select course: 
<?php
  $g_query = "SELECT * FROM registers r JOIN sections s ON r.crn = s.crn where student_id=$id";
  $gresult = mysql_query($g_query) or die(mysql_error());
  $option = '';
  if(mysql_num_rows($gresult) != 0){
	  while($row = mysql_fetch_assoc($gresult)){
		if(strtolower($row['grade_received']) == 'b' || strtolower($row['grade_received']) == 'a'){
			$option .="<option value='" . $row['course_title'] . "'>" . $row['course_title'] . "</option>";
		}
	  }
  }
?>
<select name="tutor_courses">
<?php echo $option; ?>
</select>
<input type='submit' name='tutor' value='+' />
<br>
Tutoring: 
<?php
  $query = "SELECT Course_Title FROM tutors_for WHERE tutor_student_id=$id";
  $result = mysql_query($query) or die(mysql_error());
  $ret = "";
  if(mysql_num_rows($result) != 0){
	  while($row = mysql_fetch_assoc($result)){
		if(strlen($ret) > 0)
		  $ret .= ", ";
		$ret .= $row['course_title'];
	  }
  }
?>
<input name="tutoring" type="text" size=20 readonly="readonly" value="<?php echo $ret ?>"/>
<br>
Major:
<?php
  $query = "SELECT dept_name FROM department";
  $result = mysql_query($query) or die(mysql_error());
  $option = '';
  while($row = mysql_fetch_assoc($result)){
    $option .= "<option>" . $row['dept_name'] . "</option>";
  }
?>
<select name="majors">
<?php echo $option ?>
</select>

Degree: 
<select name="degree">
<?php
  if(strcmp($degree, "BS")==0){
?>
<option value="BS" selected="selected">BS</option>
<?php
  }
  else{
?>
<option value="BS">BS</option>
<?php
  }
  if(strcmp($degree, "Master")==0){
?>  
<option value="MS" selected="selected">MS</option>
<?php
  }
  else{
?>
<option value="MS">MS</option>
<?php  
  }
  if(strcmp($degree, "Phd")==0){
?>
<option value="PhD" selected="selected">Ph.D</option>
<?php
  }
  else{
?>
<option value="Phd">Ph.D</option>
<?php
  }
?>
</select>
<br>
<br>
Previous Education
<br>
Name of Institution attended 
<input name='pinst' type='text' size=30/>
<br>
Major 
<input name="pmajor" type='text' size=30/>
<br>
Degree 
<select name='pdegree'>
<option value="BS" selected="selected">BS</option>
<option value="MS">MS</option>
<option value="Phd">Ph.D</option>
</select>
<br>
Year of graduation 
<input name='pgrad' type='text' size=10/>
<br>
GPA 
<input name="pgpa" type='text' size=5/>
<br>
<input type="submit" name="edu" value="Add Education" />
<?php
	$query = "SELECT * FROM education_history WHERE Student_ID=$id";
	$result = mysql_query($query) or die(mysql_error());
	if(mysql_num_rows($result) != 0){
		$prev = '';
		while($row = mysql_fetch_assoc($result)){
			$prev .= 'Institution: <input value=\'' . $rows['Name_Of_School'] . ' \' readonly=\'readonly\'/>\n';
			$prev .= 'Year of Graduation: <input value=\'' . $rows['Year_Graduation'] . ' \' readonly=\'readonly\'/>\n';
			$prev .= 'Degree: <input value=\'' . $rows['Degree'] . ' \' readonly=\'readonly\'/>\n';
			$prev .= 'Major: <input value=\'' . $rows['Major'] . ' \' readonly=\'readonly\'/>\n';
			$prev .= 'GPA: <input value=\'' . $rows['GPA'] . ' \' readonly=\'readonly\'/>\n';
			$prev .= '<br>';
		}
		echo $prev;
	}
?>
<?php
  }
?>
<br>
<input type="submit" name="Submit" value="Submit" />
</form>
</body>
</html>
