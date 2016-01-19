<div align=right style="margin-right: 20px;"><a href="https://academic-php.cc.gatech.edu/groups/cs4400_Group4/LogOut.php">Logout</a></div><br>
<?php
    include 'dbinfo.php';
    session_start();
    mysql_connect($dbhost, $dbusername, $dbpassword) or die("Unable to connect");
    mysql_select_db($database) or die ("Unable to select database");

?>

<table border='1'>
    <tr>
        <td>Course Code</td>
        <td>Course Name</td>
        <td>Average Grade</td>
    </tr>
    <?php
        //Admin report
        $table = '';
        $query = "SELECT * FROM course_code";
        $cquery = mysql_query($query);
        while($class = mysql_fetch_assoc($cquery)){
            //Gets average gpa for classes
            $query = "SELECT s.CRN, s.Letter, c.Course_Title, c.Code, r.Grade_Mode, r.Grade_Received, r.Student_ID FROM sections s JOIN course_code c ON s.Course_Title = c.Course_Title JOIN registers r ON s.CRN = r.CRN WHERE c.Course_Title='" . $class['Course_Title'] . "'";
            $result = mysql_query($query) or die(mysql_error());
            $avg = 0;
            $count = 0;
            $code = $class['Code'];;
            $title = $class['Course_Title'];;
            while($row = mysql_fetch_assoc($result)){
                if($row['Grade_Mode'] == "Letter"){
                    if($row['Grade_Received'] == 'A')
                        $avg += 4;
                    elseif($row['Grade_Received'] == 'B')
                        $avg += 3;
                    elseif($row['Grade_Received'] == 'C')
                        $avg += 2;
                    elseif($row['Grade_Received'] == 'D')
                        $avg += 1;
                    $count += 1;
                }
            }
            if($count != 0){
                $avg = round($avg/$count, 2);
                $table .= "<tr>\n";
                $table .= "<td>" . $code . "</td>\n";
                $table .= "<td>" . $title  . "</td>\n";
                $table .= "<td>" . $avg . "</td>\n";
                $table .= "</tr>\n";
            }
        }
        echo $table;
    ?>
</table>
<a href="https://academic-php.cc.gatech.edu/groups/cs4400_Group4/LoggedIn.php">Homepage</a>