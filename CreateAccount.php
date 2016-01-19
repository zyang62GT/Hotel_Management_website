<?php
	include 'dbinfo.php';
	//Create new account
	session_start();
	
	mysql_connect($dbhost, $dbusername, $dbpassword) or die("Unable to connect");
	mysql_select_db($database) or die ("Unable to select database");
?>

<?php
	if(isset($_POST['Register'])){
		if(strcmp($_POST['password'], $_POST['con_password']) == 0){
			//Insert into regular user
			$query = "INSERT INTO regular_user (Username, Password) VALUES('" . $_POST['username'] . "', '" . $_POST['password'] . "')";
            mysql_query($query) or die(mysql_error());
			if($_POST['type'] == 'Student')
            {
            	//Get next student id and insert into student
                $max = mysql_query("SELECT MAX(student_id) AS id FROM student") or die(mysql_error());
                $row = mysql_fetch_assoc($max);
				$query = "INSERT INTO student (Student_ID, Username) VALUES(" . $row['id'] . " + 1, '" . $_POST['username'] . "')";
            }
			else
            {
            	//Get next faculty id and insert into student
                $max = mysql_query("SELECT MAX(instructor_id) AS id FROM student") or die(mysql_error());
                $row = mysql_fetch_assoc($row);
				$query = "INSERT INTO faculty(Instructor_ID, Username) VALUES(" . $row['id'] . " + 1,, '" . $_POST['username'] . "')";
            }
			$result = mysql_query($query) or die(mysql_error());
			$row = mysql_fetch_assoc($result);
			$_SESSION['manager'] = $_POST['username'];
			header("Location: LoggedIn.php");
		}
        else
            echo "Try again\n";
		
	}
	elseif(isset($_POST['Cancel'])){
		header("Location: index.php");
	}
?>


<html>
<head>
Create Account
</head>
<body>
<form action="" method="POST">
Username: 
<input name='username' type='text' size=20 />
<br>
Password: 
<input name='password' size=20 />
<br>
Confirm Password: 
<input name='con_password' size=20 />
<br>
Type of User: 
<select name='type'>
<option value='Student'>Student</option>
<option value='Faculty'>Faculty</option>
</select>
<br>
<input type='submit' name='Cancel' value='Cancel'/>
<input type='submit' name='Register' value='Register' />

</form>
</body>
</html>