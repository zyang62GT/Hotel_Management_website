<?php
//Logs user out
$_SESSION['manager'] = "";
header("Location: index.php");
?>