<?php 
require_once('config.php');
//include('includes/heading.html'); 

mysql_select_db($database_equip, $equip);
$query_Recordset1 = "SELECT * FROM checkedout ORDER BY KitID";
$Recordset1 = mysql_query($query_Recordset1, $equip) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

?>



  <?

if($row_Recordset1['ID']>0) {  


do { 

$OutDay = date("d", strtotime($row_Recordset1['DateOut'])); 
$OutMonth = date("m", strtotime($row_Recordset1['DateOut'])); 
$OutYear = date("Y", strtotime($row_Recordset1['DateOut'])); 
$OutHour = date("G", strtotime($row_Recordset1['DateOut'])); 
$OutMinute = date("i", strtotime($row_Recordset1['DateOut']));

$InDay = date("d", strtotime($row_Recordset1['DateIn'])); 
$InMonth = date("m", strtotime($row_Recordset1['DateIn'])); 
$InYear = date("Y", strtotime($row_Recordset1['DateIn'])); 
$InHour = date("G", strtotime($row_Recordset1['DateIn'])); 
$InMinute = date("i", strtotime($row_Recordset1['DateIn']));  



$dateDiff = mktime($InHour,$InMinute,0,$InMonth,$InDay,$InYear) - mktime($OutHour,$OutMinute,0,$OutMonth,$OutDay,$OutYear);
$hourDiff = floor($dateDiff/60/60);

echo $PrevID . " - " . $TempHours . "+" .$hourDiff . "=" . ($TempHours + $hourDiff) . "<br>";

if($PrevID==""){
$PrevID = $row_Recordset1['KitID'];
}

if($PrevID == $row_Recordset1['KitID']){

$TempHours = ($TempHours + $hourDiff);
$hourDiff = 0;

} else {

echo "<b>" . $PrevID . " = " . $TempHours ;
echo '</b> - resetting<br>';
$TempHours = 0;
$hourDiff = 0;
$PrevID = $row_Recordset1['KitID'];
}

 } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); 

 }

mysql_free_result($Recordset1);
//include('includes/footer.html'); 
?>

