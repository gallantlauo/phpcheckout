<?php
require_once('../config.php'); 
// variables
$EquipmentID = $_REQUEST['EquipmentID'];
if ($AccessorytypeID != "") {
    $remove = "DELETE FROM kit_accessorytype WHERE ID='$AccessorytypeID'";
?>