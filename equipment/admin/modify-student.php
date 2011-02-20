<?php 
require_once('../config.php'); 

$StudentID = $_POST['txtStudentID'];
$FirstName = $_POST['txtFirstName'];
$LastName = $_POST['txtLastName'];
$Email = $_POST['txtEmail'];
$Phone = $_POST['txtPhone'];
$UserID = $_POST['txtUser'];
$pID = $_POST['pID'];

$modify = "UPDATE students SET StudentID='$StudentID', FirstName='$FirstName', LastName='$LastName', Email='$Email', Phone='$Phone', UID='$UserID' WHERE ID='$pID'";
mysql_select_db($database_equip, $equip);
mysql_query($modify, $equip) or die(mysql_error());

?>