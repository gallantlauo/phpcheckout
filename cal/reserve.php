<?php
require_once('../equipment/config.php'); 

$KitID = $_REQUEST['KitID'];
$StudentID = $_REQUEST['StudentID'];
$theDate = $_REQUEST['date'];
$dayDate = date('D',strtotime($theDate));
$dayToday = date("l",strtotime($theDate));

mysql_select_db($database_equip, $equip);
$q_check = sprintf("SELECT kit_class.KitID AS KitID, kit_class.ClassID AS KitClassID, student_class.StudentID AS StudentID, student_class.ClassID AS StudentClassID FROM student_class LEFT JOIN kit_class ON kit_class.ClassID = student_class.ClassID WHERE StudentID = '$StudentID' AND KitID = '$KitID' ORDER BY KitClassID ASC");
$check = mysql_query($q_check, $equip) or die(mysql_error());
$row_check = mysql_fetch_assoc($check);
$totalRows_check = mysql_num_rows($check);
if ($totalRows_check <1) {
	include('index.php');
	echo "<div id='overlay'></div>";
	echo "<meta http-equiv='refresh' content='2;URL=index.php'>";
	echo "<div id='alert' class='alert' style='visibility: visible;'>You are not enrolled <br/>in that class.<br/><br/>";
	echo "Returning to the Calendar.</div>";
} else {

mysql_select_db($database_equip, $equip);
$query_Lates = sprintf("SELECT * FROM checkedout WHERE StudentID = '$StudentID' AND DateIn = '' ORDER BY ExpectedDateIn");
$Lates = mysql_query($query_Lates, $equip) or die(mysql_error());
$row_Lates = mysql_fetch_assoc($Lates);
$totalRows_Lates = mysql_num_rows($Lates);

if ($totalRows_Lates !=0 && intval(strtotime($row_Lates['ExpectedDateIn'])) < intval(strtotime("now"))) {
	include('index.php');
	echo "<div id='overlay'></div>";
	echo "<meta http-equiv='refresh' content='3;URL=index.php'>";
	echo "<div id='alert' class='alert' style='visibility: visible;'><p>YOU HAVE ITEM(S) THAT ARE LATE!</p>";
	echo "<p>You may not make any reservations <br/>until all LATE items are returned <br/>and/or fines have been lifted and paid.</p>";
	echo "Returning to the Calendar.</div>";
	mysql_free_result($Lates);
} else {
	
mysql_select_db($database_equip, $equip);
$query_Recordset1 = sprintf("SELECT * FROM kit WHERE ID = $KitID");
$Recordset1 = mysql_query($query_Recordset1, $equip) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_equip, $equip);
$query_Recordset2 = sprintf("SELECT * FROM kit_accessorytype INNER JOIN accessorytype ON accessorytype.ID = kit_accessorytype.AccessorytypeID WHERE KitID = $KitID;");
$Recordset2 = mysql_query($query_Recordset2, $equip) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_equip, $equip);
$query_Recordset3 = sprintf("SELECT * FROM students WHERE StudentID = $StudentID");
$Recordset3 = mysql_query($query_Recordset3, $equip) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_equip, $equip);
$query_Recordset4 = sprintf("SELECT * FROM kit_class INNER JOIN student_class ON student_class.ClassID = kit_class.ClassID WHERE student_class.StudentID = $StudentID AND kit_class.kitID = $KitID");
$Recordset4 = mysql_query($query_Recordset4, $equip) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4); 

include('includes/heading.html');

if ($row_Recordset1['ContractRequired'] == 1) { 
echo("<b>Student must sign an individual contract for this kit.</b>");
}
?>
<script type="text/javascript">
function hide() {
$('alert').style.visibility = "hidden";
}
</script>
<p>
<form name="frmCheckOut" action="reserve_action.php" method="post">

<input type="hidden" name="FirstName" value="<?php echo $row_Recordset3['FirstName']; ?>">
<input type="hidden" name="LastName" value="<?php echo $row_Recordset3['LastName']; ?>">
<input type="hidden" name="KitName" value="<?php echo $row_Recordset1['Name']; ?>">
<input type="hidden" name="ContractRequired" value="<?php echo $_REQUEST['ContractRequired']; ?>">


<p>
<div id="tag-br">
<div id="tag-top"><?php echo $row_Recordset3['FirstName']; ?> <?php echo $row_Recordset3['LastName']; ?></div>
<div id="tag-info"> is reserving the <?php echo $row_Recordset1['Name']; ?>.</div></div>
<div id="tag-br">
<div id="tag">Reserving:</div>
<div id="tag-info">
<?php 
$ServerCheckHours = 0;
 do {
 	if($row_Recordset4['CheckHours'] > $ServerCheckHours){
 		$ServerCheckHours = $row_Recordset4['CheckHours'];
	}
	if($row_Recordset4['OverNightAllowed'] > $overNight){
		$overNight = $row_Recordset4['OverNightAllowed'];
	}
} while ($row_Recordset4 = mysql_fetch_assoc($Recordset4)); 
?>
<?php echo date("D, F d, g:i a", strtotime($theDate)); ?></div></div>
<div id="tag-br">
<div id="tag">Expected Back:</div>
<div id="tag-info">
<?php 

	$Year = date('Y', strtotime($theDate));
	
	$Month = date('m', strtotime($theDate));
	
//	if ($Month<10) {
//	$Month = "0".$Month;
//	}
	
	$Hours = substr($dueHours,0,2);

	$Minutes = substr($dueHours,3,2);
	
	
	//DUE BACK NEXT DAY / 24 HOURS
	$Day = date('d', strtotime($theDate));


	//LOGIC FOR END OF MONTH
	
	switch($Month){
	
	case 1:
		$LastDay = 31;
		break;
	case 2:
		$LastDay = 28;
		break;
	case 3:
		$LastDay = 31;
		break;
	case 4:
		$LastDay = 30;
		break;
	case 5:
		$LastDay = 31;
		break;
	case 6:
		$LastDay = 31;
		break;
	case 7:
		$LastDay = 31;
		break;
	case 8:
		$LastDay = 30;
		break;
	case 9:
		$LastDay = 31;
		break;		
	case 10:
		$LastDay = 30;
		break;
	case 11:
		$LastDay = 30;
		break;		
	case 12:
		$LastDay = 31;
		break;	
	}
	
	if (($ServerCheckHours + date('G')) < 24) {
		$Hours = ($Hours + $ServerCheckHours);
	}
	else if ($ServerCheckHours == 24 && $overNight) {
		$Day = ($Day+1);
	} else {
		$Hours = 23; // this sets it to 11pm instead of going to next day, further down this is reset to correspond to closeTimes.
	}
	
	if ($ServerCheckHours > 24 && overNight) {
		$Day = $Day + round($ServerCheckHours/24);
		$Hours = $Hours + round(($ServerCheckHours%24)*24);
	}


	//IS LAST DAY OF MONTH?
	if($Day>$LastDay){
		$Month = ($Month+1);
		$Day = 1;
	}
		
	$returndateSQL = $Year."-".$Month."-".$Day." ".$dueHours;
	
	//IF NOT OPEN weekends
	if(!$weekends) {
		if (date('D', strtotime($returndateSQL)) == $dayClosed1) {
			if (date('D', strtotime($returndateSQL.'+1 day')) == $dayClosed2) {
				$Day = ($Day + 2);
			} else {
				$Day = ($Day + 1);
			}
		}
		
		//IS LAST DAY OF MONTH?
		if($Day>$LastDay){
			$Month = ($Month+1);
			$Day = 1;
		}
		
		$returndateSQL = $Year."-".$Month."-".$Day." ".$dueHours;
		
		//RULES FOR SPECIFC DAY CLOSED (PRESUMABLY SUNDAY)
		if (date('D', strtotime($returndateSQL)) == $dayClosed2) {
			$Day = ($Day + 1);
		}
		
		//IS LAST DAY OF MONTH?
		if($Day>$LastDay){
			$Month = ($Month+1);
			$Day = 1;
		}
	}
	
	$returndateSQL = $Year."-".$Month."-".$Day." ".$dueHours;
	
	// figure out hours here
	switch (date('D',strtotime($returndateSQL))) {
	
		case "Sun" : 
					$openTime = $sunOpen;
					$closeTime = $sunClose;
					break;
		case "Mon" :
					$openTime = $monOpen;
					$closeTime = $monClose;
					break;
		case "Tue" :
					$openTime = $tueOpen;
					$closeTime = $tueClose;
					break;
		case "Wed" :
					$openTime = $wedOpen;
					$closeTime = $wedClose;
					break;
		case "Thu" :
					$openTime = $thuOpen;
					$closeTime = $thuClose;
					break;
		case "Fri" :
					$openTime = $friOpen;
					$closeTime = $friClose;
					break;
		case "Sat" :
					$openTime = $satOpen;
					$closeTime = $satClose;
					break;
	}
	
	if ($Hours <= $openTime) {
		$Hours = $openTime;
		if ($Minutes < 30) {
			$Minutes = 30;
		}
	}
	
	if ($Hours >= $closeTime-1) {
		$Hours = $closeTime-1;
		if ($Minutes > 30) {
			$Minutes = 30;
		}
	}
	
	//date formate for sql 0000-00-00 00:00:00
	$returndateSQL = $Year."-".$Month."-".$Day." ".$dueHours;
	
	//if reserved, send back to main
	mysql_select_db($database_equip, $equip);
	$r_check = sprintf("SELECT * FROM checkedout WHERE ReserveDate >= CURDATE() AND KitID = '$KitID'");
	$res = mysql_query($r_check, $equip) or die(mysql_error());
	//$row_res = mysql_fetch_assoc($res);
	$totalRows_res = mysql_num_rows($res);
	$rtndate = substr($returndateSQL,0,10);
	$rtndate2 = date('Y-m-d',strtotime($rtndate.'-1 day'));
	$today = date('Y-m-d',strtotime($theDate));
	$tomorrow = date('Y-m-d',strtotime($theDate.'+1 day'));
	//echo $rtndate;
	if ($totalRows_res > 0) {
		$reserved_array = array();
		mysql_data_seek($res,0);
		while ($loopReserved = mysql_fetch_assoc($res)) {
	  		array_push($reserved_array, $loopReserved['ReserveDate']);
		}
		//print_r($reserved_array);
		if (in_array($rtndate, $reserved_array) || in_array($rtndate2, $reserved_array) || in_array($today, $reserved_array) || in_array($tomorrow, $reserved_array)){
			//if returndateSQL == reservedate || returndateSQL == reservedate - 1
			//include('index.php');
			echo "<meta http-equiv='refresh' content='2;URL=index.php?date1=$today'>";
			echo "<div id='overlay'></div>";
			echo "<div id='alert' class='alert' style='visibility: visible;'>Conflicts with another reservation.<br/>You must checkout in person.<br/><br/>";
			echo "Returning to the Calendar.</div>";
		}
	}

?>
<span id="newDay"><? echo date('D', strtotime($returndateSQL));?></span>, <span id="newMonth"><? echo date('F', strtotime($returndateSQL));?></span> <span id="newDate"><? echo date('d', strtotime($returndateSQL));?></span>, BEFORE <? echo date('g:i a', strtotime($returndateSQL));?></div></div>
<input type="hidden" id="ReturnDate" name="ReturnDate" value="<? echo date('Y-m-d H:i:s', strtotime($returndateSQL)); ?>">
<input type="hidden" id="ReserveDate" name="ReserveDate" value="<? echo $theDate; ?>">
</span>
<? 
if ($row_Recordset1['FineAmount']!="") { 
 echo "Fine: $" . number_format($fineAmount,2); 
 echo  " per ";
 echo $fineFreq/60;
 echo " Minutes.";
 } 
?>
<div id="tag-br"></div>
 <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><h2><?php echo $row_Recordset1['Name']; ?></h2><br/></td>
    <td></td>
  </tr>
  <tr>
    <td><div align="center">
    <? 
    $Image = $row_Recordset1['Image'];
    if($Image!=""){ 
    	echo "<img src='$root/images/$Image' width='350' />";
    } ?>
    <br>
    </div></td>
    <td valign="top">
    <div id="acessories_box">
    <strong>Accessories:</strong><br>
	<p>
		<ul>
		<?
		if ($AccessoryName=""){
			echo"<p>No Accessories are available for this item.</p>";
		} else {
		$i = 0;
		do { 
		$AccessoryName = $row_Recordset2['Name'];
		echo"<li class='accessoryText'>$AccessoryName</li>";
		$i++;
		} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2)); 
		}
		?>
		</ul>
	</p>
	</div>
    </td>
  </tr>
</table>
 <br>
 <HR>
<input type="hidden" name="User" value="<? echo $Username ?>">
<input type="hidden" name="KitID" value="<? echo $KitID ?>">
<input type="hidden" name="StudentID" value="<? echo $StudentID ?>">
<p>
Notes:<br>
<textarea cols=60 rows=5 name="Notes" readonly="readonly"><?php echo $row_Recordset1['Notes']; ?></textarea></p>
<p><input type="submit" name="Submit" value="Reserve"> <input type="button" name="back" value="Cancel" onClick="javascript:history.back();"></p>
</form>

<? 
include('includes/footer.html'); 
mysql_free_result($Recordset1);
mysql_free_result($Recordset2);
mysql_free_result($Recordset3);
mysql_free_result($Recordset4);
}}
?>
