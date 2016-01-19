<div align=right style="margin-right: 20px;"><a href="https://academic-php.cc.gatech.edu/groups/cs4400_Group4/LogOut.php">Logout</a></div><br>
<?php
  //If user is logged in
  include 'dbinfo.php';
    session_start();

    mysql_connect($dbhost, $dbusername, $dbpassword) or die("Unable to connect");
    mysql_select_db($database) or die ("Unable to select database");
?>

<?php
//Send administrator to AdminReport
if(mysql_num_rows(mysql_query("SELECT * FROM administrators WHERE username='" . $_SESSION['manager'] ."'")) != 0){
    header("Location: AdminReport.php");
}

  if(isset($_POST['option'])){
      $res = mysql_query("SELECT * from student where username='" . $_SESSION['manager'] . "'");
      //echo "SELECT * from student where username='" . $_SESSION['manager'] . "'";
    //If student
    if($_POST['option'] == "services"){
        if(mysql_num_rows($res) != 0)
            header("Location: StudentServices.php");
        else
            header("Location: FacultyServices.php");
    }
    //If faculty
    else{
        if(mysql_num_rows($res) != 0)
            header("Location: PersonalStudInfo.php");
        else
            header("Location: PersonalProfInfo.php");

    }
  }
  else{
?>

<html>
<head>
</head>
<body>
<form action="" method="POST">
<p>
<input type="Radio" name="option" value="personal"/>
Personal Information
</p>
<p>
<input type="Radio" name="option" value="services"/>
Services
</p>
<input type="submit" name="Next" value="next" />
</form>
</body>
</html>

<?php
  }
  ?>

