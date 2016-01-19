<div align=right style="margin-right: 20px;"><a href="https://academic-php.cc.gatech.edu/groups/cs4400_Group4/LogOut.php">Logout</a></div><br>
<?php
	include 'dbinfo.php';
?>

<html>
	<head>
	</head>
	<body>
		<?php
			session_start();
			$username = $_SESSION['manager'];

			mysql_connect($dbhost, $dbusername, $dbpassword) or die("Unable to connect");
			mysql_select_db($database) or die ("Unable to select database");

			$query = "SELECT Dept_Name FROM department";
			$result = mysql_query($query) or die(mysql_error());
		?>
		<p>Term: Fall 2012</p>
		<form action="CourseSelection.php" method="POST">
			<input type="hidden" name="Term" value="Fall 2012"/>
			<select name="Department">
			<?php
				for($j = 0; $j < mysql_num_rows($result); $j++) {
					$row = mysql_fetch_assoc($result);
					echo "<option>".$row['Dept_Name']."</option>";
				}	
			?>
			</select>
			<br/>
			<input type="submit" name="next" value="next"/>
		</form>
	</body>
</html>