<?php
require_once('../config.php'); 
// variables$modEquip = $_REQUEST['modEquip'];
$EquipmentID = $_REQUEST['EquipmentID'];$StudentID = $_REQUEST['StudentID'];$accessoryName = $_REQUEST['accessory'];$AccessorytypeID = $_REQUEST['AccessorytypeID'];// remove accessory from kit
if ($AccessorytypeID != "") {
    $remove = "DELETE FROM kit_accessorytype WHERE ID='$AccessorytypeID'";    mysql_select_db($database_equip, $equip);    mysql_query($remove, $equip) or die(mysql_error());}
?>
