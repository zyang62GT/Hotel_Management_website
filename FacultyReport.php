<?php
    //Faculty report
    include 'dbinfo.php';
    $username = $_SESSION['manager'];


    mysql_connect($dbhost, $dbusername, $dbpassword) or die("Unable to connect");
    mysql_select_db($database) or die ("Unable to select database");

    $query = "SELECT Course_Title, Code FROM course_code";
    $result = mysql_query($query) or die(mysql_error());

    echo "<table>";
    echo "<tr><td>Course Code</td><td>Course Name</td><td>Number of meetings with tutors</td><td>Average grade of students</td></tr>";
    while($row = mysql_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$row['Code']."</td>";
        $courseTitle = $row['Course_Title'];
        echo "<td>".$courseTitle."</td>";
        echo "<td><p>>3</p>";
        //Get each student visit
        $query = "SELECT sections.CRN, logs_visit.Student_ID, registers.Grade_Received, registers.Grade_Mode FROM course_code INNER JOIN sections ON course_code.Course_Title = sections.Course_Title INNER JOIN logs_visit ON sections.CRN = logs_visit.CRN INNER JOIN registers ON registers.CRN=sections.CRN WHERE sections.Course_Title = '" . $row['Course_Title'] . "' GROUP BY Student_ID HAVING COUNT(*) > 3";
        $firstResult = mysql_query($query) or die(mysql_error());
        $classAvg = 0;
        $count = 0;        
        while($studentResult = mysql_fetch_assoc($firstResult)) {
            $studentID = $studentResult['Student_ID'];
            $CRN = $studentResult['CRN'];
            //Get student grades then average after loop
            $newQuery = "SELECT Grade_Received, Grade_Mode FROM registers INNER JOIN sections ON registers.CRN = sections.CRN WHERE Student_ID = $studentID AND registers.CRN = ".$CRN."";
            $newQueryResult = mysql_query($newQuery) or die(mysql_error());
            $newRow = mysql_fetch_assoc($newQueryResult);
            if($newRow['Grade_Mode'] == 'Letter') {
                $count += 1;
                if($newRow['Grade_Received'] == 'A') {
                    $classAvg +=4;
                }
                else if ($newRow['Grade_Received'] == 'B') {
                    $classAvg +=3;
                }
                else if ($newRow['Grade_Received'] == 'C') {
                    $classAvg +=2;
                }
                else if ($newRow['Grade_Received'] == 'D') {
                    $classAvg +=1;
                }
            }
        }
        $classAvg = round($classAvg/$count, 2);
        echo "<td>".$classAvg."</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td><p>1-3</p>";
        $query = "SELECT sections.CRN, logs_visit.Student_ID, registers.Grade_Received, registers.Grade_Mode FROM course_code INNER JOIN sections ON course_code.Course_Title = sections.Course_Title INNER JOIN logs_visit ON sections.CRN = logs_visit.CRN INNER JOIN registers ON registers.CRN=sections.CRN WHERE sections.Course_Title = '" . $row['Course_Title'] . "' GROUP BY Student_ID HAVING COUNT(*) > 0 AND COUNT(*) < 4";
        $firstResult = mysql_query($query) or die(mysql_error());
        $classAvg = 0;
        $count = 0;
        while($studentResult = mysql_fetch_assoc($firstResult)) {
            $studentID = $studentResult['Student_ID'];
            $CRN = $studentResult['CRN'];
            $newQuery = "SELECT Grade_Received, Grade_Mode FROM registers INNER JOIN sections ON registers.CRN = sections.CRN WHERE Student_ID = $studentID AND sections.CRN = $CRN";
            $newQueryResult = mysql_query($newQuery) or die(mysql_error());
            $newRow = mysql_fetch_assoc($newQueryResult);
            if($newRow['Grade_Mode'] == 'Letter') {
                $count += 1;
                if($newRow['Grade_Received'] == 'A') {
                    $classAvg +=4;
                }
                else if ($newRow['Grade_Received'] == 'B') {
                    $classAvg +=3;
                }
                else if ($newRow['Grade_Received'] == 'C') {
                    $classAvg +=2;
                }
                else if ($newRow['Grade_Received'] == 'D') {
                    $classAvg +=1;
                }
            }
        }
        $classAvg = round($classAvg/$count, 2);
        echo "<td>".$classAvg."</td>";
        echo "<tr/>";
        echo "<tr>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td><p>0</p>";
        $query = "SELECT sections.CRN, registers.Grade_Received, registers.Grade_Mode, registers.Student_ID FROM course_code INNER JOIN sections ON course_code.Course_Title = sections.Course_Title INNER JOIN registers ON registers.CRN=sections.CRN WHERE sections.Course_Title = '" . $row['Course_Title'] . "' AND registers.crn NOT IN(select student_id from logs_visit) GROUP BY Student_ID";
        $firstResult = mysql_query($query) or die(mysql_error());
        $classAvg = 0;
        $count = 0;
        while($studentResult = mysql_fetch_assoc($firstResult)) {
            $studentID = $studentResult['Student_ID'];
            $CRN = $studentResult['CRN'];
            $newQuery = "SELECT Grade_Received, Grade_Mode FROM registers INNER JOIN sections ON registers.CRN = sections.CRN WHERE Student_ID = $studentID AND sections.CRN = $CRN";
            $newQueryResult = mysql_query($newQuery) or die(mysql_error());
            $newRow = mysql_fetch_assoc($newQueryResult);
            if($newRow['Grade_Mode'] == 'Letter') {
                $count++;
                if($newRow['Grade_Received'] == 'A') {
                    $classAvg +=4;
                }
                else if ($newRow['Grade_Received'] == 'B') {
                    $classAvg +=3;
                }
                else if ($newRow['Grade_Received'] == 'C') {
                    $classAvg +=2;
                }
                else if ($newRow['Grade_Received'] == 'D') {
                    $classAvg +=1;
                }
            }
        }
        $classAvg = round($classAvg/$count, 2);
        echo "<td>".$classAvg."</td>";
        echo "<tr/>";
    }
    echo "</table>";
?>

