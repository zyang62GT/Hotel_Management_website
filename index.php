<?php
	include 'dbinfo.php';
?>

<?php
	session_start();
	if(isset($_POST['username']) && isset($_POST['password'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		mysql_connect($dbhost, $dbusername, $dbpassword) or die("Unable to connect");
		mysql_select_db($database) or die ("Unable to select database");

		$sql_query = "SELECT password FROM regular_user WHERE username = '$username'";
		$result = mysql_query($sql_query) or die (mysql_error());

		if(mysql_num_rows($result) == 1) {
			$row = mysql_fetch_assoc($result);
			if($password == $row['password']) {
				$_SESSION['manager'] = $username;
				header("Location: LoggedIn.php");
			}
			else {
				header("Location: notLoggedIn.php");
			}
		}
		else {
			header("Location: User does not exist");
			$err = "Incorrect username for student";
		}
	}
	elseif(isset($_POST['Create Account'])){
		header("Location: CreateAccount.php");
	}

	else {
		echo "<html>";
		echo "<head>";
		echo "</head>";
		echo "<body>";
		echo "<form action=\"\" method=\"POST\">"; 
		echo "<p>Username:";  
		echo "<input name=\"username\" size=\"20\" maxlength=\"20\"/>"; 
		echo "</p>"; 
		echo "<p>Password:";
		echo "<input name=\"password\" size=\"20\" maxlength=\"20\"/>";
		echo "</p>";
		echo "<input type=\"submit\" name=\"login\" value=\"Login\" />"; 
		echo "<input type=\"submit\" name=\"CreateAccount\" value=\"CreateAccount\" />";
		echo "</form>"; 
		echo "</body>"; 
		echo "</html>"; 
	}
?>
