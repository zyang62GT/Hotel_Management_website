<div align=right style="margin-right: 20px;"><a href="https://academic-php.cc.gatech.edu/groups/cs4400_Group4/LogOut.php">Logout</a></div><br>
<?php
    //Faculty information
    include 'dbinfo.php';
    session_start();

    mysql_connect($dbhost, $dbusername, $dbpassword) or die("Unable to connect");
    mysql_select_db($database) or die ("Unable to select database");
    $username = $_SESSION['Username'];
?>

<html>
<head>
</head>
<body>

<?php
    $username = $_SESSION['manager'];
    if(isset($_POST['Submit'])){
        if($_POST['address'] != '' && $_POST['perm_address'] != '' && $_POST['gender'] != '' && $_POST['contact'] != ''){
            //Updates the faculty information
            $query1 = "UPDATE faculty SET Position='" . $_POST['position'] . "', Dept_ID=" . $_POST['department'] . " where username='$username'";
            $mysqldate = $_POST['dob'];
            $query2 = "UPDATE regular_user SET Date_Of_Birth='$mysqldate', Address='" . $_POST['address'] . "', Permanent_Address='" . $_POST['perm_address'] . "', Gender='" . $_POST['gender'] . "', Contact_No=" . $_POST['contact'] . ", Name='" . $_POST['name'] . "' where Username='$username'";
            $query4 = "SELECT Instructor_ID FROM regular_user INNER JOIN faculty ON regular_user.Username = faculty.Username WHERE Username = '$username'";
            $result = mysql_query($query4);
            $row = mysql_fetch_assoc($result);
            $instructor_id = $row['Instructor_ID'];
            $query3 = "UPDATE faculty_research_interests SET Research_Interest='" . $_POST['interests'] . "' WHERE Instructor_ID= '$instructor_id'";
            mysql_query($query1) or die(mysql_error());
            mysql_query($query2) or die(mysql_error());
            mysql_query($query3) or die(mysql_error());
            header("Location: LoggedIn.php");
        }
        else{
            echo "TRY AGAIN";
        }
    }
    elseif(isset($_POST['deptButton'])){
        //Updates department
        $query = "UPDATE faculty SET Dept_ID='" . $_POST['department'] . "' where Username='" . $_SESSION['manager'] . "'";
        echo $query;
        mysql_query($query) or die(mysql_error());
    }elseif(isset($_POST['courseButton'])){
    }elseif(isset($_POST['sectionButton'])){
        //Check sections
        $query = "SELECT * FROM sections WHERE instructor_id = '" . $_SESSION['id'] . "' AND term='Fall 2012' GROUP BY Term, Course_Title";
        $result = mysql_query($query) or die(mysql_error());
        if(mysql_num_rows($result) > 0){
            //Update section
            $query = "UPDATE sections SET Instructor_ID=NULL WHERE Course_Title <> '" . $_POST['courses'] . "' AND Term='Fall 2012' AND Instructor_ID=" . $_SESSION['id'];
            mysql_query($query) or die(mysql_error());
        }
        $query = "UPDATE sections SET Instructor_ID=" . $_SESSION['id'] . " WHERE Course_Title='" . $_POST['courses'] . "' AND Letter='" . $_POST['section'] . "' AND   Term='Fall 2012'";
        mysql_query($query);
    }
    //Get faculty member
    $query = "SELECT * FROM faculty NATURAL JOIN regular_user WHERE username='" . $username . "'";
    $result = mysql_query($query);
    if(mysql_num_rows($result) == 1)
    {
        $row = mysql_fetch_assoc($result);
        $name = $row['Name'];
        $address = $row['Address'];
        $perm_address = $row['Permanent_Address'];
        $email = $row['Email_ID'];
        $dob = $row['Date_Of_Birth'];
        $gender = $row['Gender'];
        $contact = $row['Contact_No'];
        $position = $row['Position'];
        $dept_id = $row['Dept_ID'];
        $id = $row['Faculty_ID'];
        $_SESSION['id'] = $id;
    }elseif(mysql_num_rows($result) == 0){
        $name = '';
        $address = '';
        $perm_address = '';
        $email = '';
        $dob = '';
        $gender = '';
        $contact = '';
        $position = '';
        $dept_id = '';
        $id = '';

    }
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
            <option value="Male" selected="selected">Male</option>
            <option value="Female">Female</option>
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
    Position:
    <select name='position'>
        <?php
            if($position == "professor"){
        ?>
            <option value="professor" selected="selected">Professor</option>
        <?php
            }else{
        ?>
            <option value="professor">Professor</option>
        <?php
            }if($position == "assc_professor"){
        ?>
            <option value="assc_professor" selected="selected">Associate Professor</option>
        <?php
            }else{
        ?>
            <option value="assc_professor">Associate Professor</option>
        <?php
            }if($position == "ass_professor"){
        ?>
            <option value="ass_professor">Assistant Professor</option>
        <?php
            }else{
        ?>
            <option value="ass_professor">Assistant Professor</option>
        <?php
    }
        ?>
    </select>
    <br>
    Department:
    <?php
        //Get all departments
        $query = "SELECT * FROM department";
        $result = mysql_query($query) or die(mysql_error());
        $ddl = "<select name='department'>\n";
        while($row = mysql_fetch_assoc($result)){
            $ddl .= "<option value='" . $row['Dept_ID'] . "' ";
            if($row['Dept_ID'] == $dept_id){
                $ddl .= "selected='selected' ";
            }
            $ddl .= "/>" . $row['Dept_Name'] . "</option>\n";
        }
        $ddl .= "</select>";
        echo $ddl;
    ?>
    <br>
        <input type='submit' name='deptButton' value='Department' />
    <?php
        if(isset($_POST['deptButton']) || isset($_POST['courseButton']) || isset($_POST['sectionButton'])){
    ?>
        <br>
        Course:
    <?php
            //Get all course titles
            $query = "SELECT Course_Title FROM offers WHERE Dept_ID=" . $dept_id;
            $result = mysql_query($query);
            $courses = "<select name='courses'>\n";
            while($row = mysql_fetch_assoc($result)){
                $courses .= "<option value='" . $row['Course_Title'] . "' ";
                if($row['Term'] == "Fall 2012"){
                    $courses .= "selected='selected' ";
                }
                $courses .= "/>" . $row['Course_Title'] . "</option>\n";
            }
            $courses .= "</select>";
            echo $courses;

    ?>
        <br>
        <input type='submit' name='courseButton' value='Course' />
    <?php
        }
        if(isset($_POST['courseButton'])){
    ?>
        <br>
        Section:
    <?php
            //Get all section letters
            $query = "SELECT Letter FROM sections WHERE Course_Title='" . $_POST['courses'] . "'";
            $result = mysql_query($query);
            $sections = "<select name='courses'>\n";
            while($row = mysql_fetch_assoc($result)){
                $sections .= "<option value='" . $row['Letter'] . "' />" . $row['Letter'] . "</option>\n";
            }
            $sections .= "</select>";
            echo $sections;
    ?>
        <br>
        <input type='submit' name='sectionButton' value='Section' />

    <?php
        }
    ?>
    <br>
    Resarch Interests:
    <?php
        //Get all research interests
        $query = "SELECT Research_Interest FROM faculty_research_interests WHERE Instructor_ID=" . $id;
        $result = mysql_query($query);
        $ints = "<input name='interests' value='";
        $interests = '';
        while($row = mysql_fetch_assoc($result)){
            if(strlen($interests) > 0)
                $interests .=", ";
            $interests .= $row['Research_Interest'];
        }
        $ints .= $interests . "' />";
        echo $ints;
    ?>
    <br>
    <input type="submit" name="Submit" value="Submit" />
</form>
</body>
</html>