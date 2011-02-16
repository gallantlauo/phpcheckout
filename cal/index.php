<?php
require_once('classes/tc_calendar.php');
require_once('../equipment/config.php');
include('includes/heading.html'); 

if ($ldap_cal) { 
	mysql_select_db($database_equip, $equip);
	$query_Recordset1 = sprintf("SELECT * FROM students WHERE UID = '$Username'");
	$Recordset1 = mysql_query($query_Recordset1, $equip) or die(mysql_error());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	//$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	$sid=$row_Recordset1['StudentID']; 
}

$class = $_REQUEST['class'];
$theDate = isset($_REQUEST["date1"]) ? $_REQUEST["date1"] : "";
?>
<div id="top_content">
	<div id="cal">
    <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <p class="largetxt"><b>Select Date: </b></p>
      <?php
      $thisYear = date("Y");
      $thisDate = date("Y-m-d");
      $thisRange = date("Y-m-d",strtotime("+7 days"));
      if (strtotime($theDate) > strtotime($thisRange) || strtotime($theDate) < strtotime($thisDate)) {
        $theDate = $thisDate;
      }
  	  $myCalendar = new tc_calendar("date1");
  	  $myCalendar->setIcon("images/iconCalendar.gif");
  	  if (!empty($theDate)) {
        $newDate = strtotime($theDate);
        $myCalendar->setDate(date('d', $newDate), date('m', $newDate), date('Y', $newDate));
        $nowDate = date("D, F d, Y", $newDate);
			} else {
        $myCalendar->setDate(date('d'), date('m'), date('Y'));
        $theDate = date("Y-m-d");
        $nowDate = date("D, F d, Y");
    	}
  	  $myCalendar->setPath("./");
  	  $myCalendar->setYearInterval($thisYear, $thisYear);
  	  $myCalendar->dateAllow($thisDate, $thisRange, false);
  	  $myCalendar->startMonday(true);
			//$myCalendar->setDateFormat('d, m, Y');
  	  //$myCalendar->autoSubmit(true, "", "index.php");
  	  $myCalendar->autoSubmit(true, "form1");
  	  $myCalendar->writeScript();
  	  echo "<input type='hidden' name='class' id='class' value='$class'>";
  	  ?>
    </form>
	</div>
	<div id="class_selector">
	<h1>Selected Date: </h1><h2><strong><? echo $nowDate; ?></strong></h2><br/>
  	<?php

    // LIST BY CLASS
    mysql_select_db($database_equip, $equip);
    $classes = mysql_query("SELECT * FROM class ORDER BY class.Name") or die(mysql_error());  
  	?>

  	<form name="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    	<select name="class" size="1" id="class" style="margin-left: 10px; margin-bottom: 5px;" onChange="this.form.submit();">
			<? while($option = mysql_fetch_array( $classes )) {
        // Print out the contents of each row (class) into an option
    		if ($class == "") { $class = $option[Name]; }
    		if ($class == "$option[Name]") { 
    			$echo = " selected"; 
    		} else { 
    			$echo = "";
    		}
    		echo "<option $echo value='$option[Name]'>$option[Name]</option>";
    		}
    	?>
    	</select>
		<input type="hidden" name="date1" value="<?php echo $theDate; ?>">
  	</form>
	</div>
	<div id="legend"><div id="smBox" class="closed" >Closed</div><div id="smBox" class="open" >Open</div><div id="smBox" class="reserved" >Reserved</div><div id="smBox" class="checked" >Checked</div><div id="smBox" class="repairs" >Repairs</div></div>
</div>
<?php
// get item names based on selected class
mysql_select_db($database_equip, $equip);
$selectedClass = "SELECT kit.Name as KitName, kit.ID as KitID, kit.Repair as Repair, class.Name as ClassName FROM kit LEFT JOIN kit_class ON kit_class.KitID = kit.ID LEFT JOIN class ON class.ID = kit_class.ClassID WHERE class.Name = '$class' ORDER BY kit.Name";
$kitNames = mysql_query($selectedClass, $equip) or die("Could not perform select query - " . mysql_error());
$totalRows_kitNames = mysql_num_rows($kitNames);

// get repaired info and make an array of repair items
$selectedClass = "SELECT * FROM kit WHERE Repair = 1 ORDER BY Name";
$repairs = mysql_query($selectedClass, $equip) or die("Could not perform select query - " . mysql_error());
$totalRows_repairs = mysql_num_rows($repairs);

$repair = array();
if ($totalRows_repairs > 0) { mysql_data_seek($repairs,0); }

while ($loopRepair = mysql_fetch_assoc($repairs)) {
  array_push($repair, $loopRepair['ID']);
}

// get items reserved today and items that are checked out and due in future
$find = "SELECT kit.Name as kitName, kit.ID as KitID, checkedout.ID as CheckedID, checkedout.ReserveDate, checkedout.DateOut, checkedout.DateIn, checkedout.ExpectedDateIn FROM kit LEFT JOIN checkedout ON checkedout.KitID = kit.ID WHERE ReserveDate >= CURDATE() OR DateOut <= UTC_TIMESTAMP() AND DateIn = '' ORDER BY kitName";
$kitReserved = mysql_query($find, $equip) or die("Could not perform select query - " . mysql_error());
$row_kitReserved = mysql_fetch_assoc($kitReserved);
$reservedKitID = $row_kitReserved['KitID'];
$totalRows_kitReserved = mysql_num_rows($kitReserved);

//make an array of currently checked out and reserved items
$item = array();
if ($totalRows_kitReserved > 0) { mysql_data_seek($kitReserved,0); }
while ($loopReserved = mysql_fetch_assoc($kitReserved)) {
  $item[$loopReserved['CheckedID']] = $loopReserved;
}

//debug array of checked out and reserved items
//print_r($item);

echo "<div id='arrows'>";
if (strtotime($theDate) > strtotime($thisDate)) {
  $prevDate = date("Y-m-d",strtotime($theDate."- 1 days"));
  echo "<a class='active l' href='index.php?date1=$prevDate'><< Previous</a>";
} else { 
	echo "<span class='l'><< Previous</span>"; 
}

if (strtotime($theDate) < strtotime($thisRange)) {
  $nextDate = date("Y-m-d",strtotime($theDate."+ 1 days"));
  echo "<a class='active r' href='index.php?date1=$nextDate'>Next >></a>";
} else { 
	echo "<span class='r'>Next >></span>"; 
}
echo "</div>";

//get days
$day = date("D",strtotime($theDate));
$day = array($day);
$date = date("Y-m-d",strtotime($theDate));
$date = array($date);
for ($i =1; $i < 7; $i++){
  array_push($day, date("D",strtotime($theDate." + $i days")));
  array_push($date, date("Y-m-d",strtotime($theDate." + $i days")));
}

?>
<div id="table">
<table width="735" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td class="box">Items</td>
    <?php for ($i=0; $i<7; $i++){ echo "<td class='box'>".$day[$i]." ".date("n/j",strtotime($date[$i]))."</td>\n"; } ?>
  </tr>
	<?
  //get item names
	if ($totalRows_kitNames > 0) { mysql_data_seek($kitNames,0); }
	while($kitName = mysql_fetch_array( $kitNames )) {
		echo "<tr>\n";
		echo "  <td class='box'>".$kitName['KitName']."</td>\n";
		$currKitID = $kitName['KitID'];
		
		//make array of reserved dates per kit id
		$kits = array();
		if ($totalRows_kitReserved > 0) { mysql_data_seek($kitReserved,0); }
		while ($loopRes = mysql_fetch_assoc($kitReserved)) {
      if (array_key_exists($loopRes['KitID'], $kits)) {
        array_push($kits[$loopRes['KitID']], $loopRes['ReserveDate']);
			} else {
        $kits[$loopRes['KitID']] = array($loopRes['ReserveDate']);
			}
		}

		//make array of checked dates per kit id
		$checked = array();
		if ($totalRows_kitReserved > 0) { mysql_data_seek($kitReserved,0); }
		while ($loopCheck = mysql_fetch_assoc($kitReserved)) {
      if ($loopCheck['ReserveDate'] == null || $loopCheck['ReserveDate'] == '') {
        $checked[$loopCheck['KitID']] = array(substr($loopCheck['DateOut'],0,10),substr($loopCheck['ExpectedDateIn'],0,10));
			}
		}

    //each day (selected day)
		for ($i=0; $i<7; $i++){
			if ($day[$i] == $dayClosed1 || $day[$i] == $dayClosed2) {
        echo "<td class='box closed'>&nbsp;</td>\n";
 			} elseif (in_array($currKitID,$repair)) {
					echo "<td class='box repairs'>&nbsp;</td>\n";
			} elseif (array_key_exists($currKitID,$kits)) {
				if (in_array($date[$i], $kits[$currKitID]) || in_array(date("Y-m-d",strtotime($date[$i]."- 1 days")), $kits[$currKitID]) || in_array(date("Y-m-d",strtotime($date[$i]."- 2 days")), $kits[$currKitID]))
				{
					echo "<td class='box reserved'>&nbsp;";
					//echo "<font color='red'>*reserved*</font>";
					echo "</td>\n";
				} 
				elseif (in_array(date("Y-m-d",strtotime($date[$i]."- 4 days")), $kits[$currKitID]) || in_array(date("Y-m-d",strtotime($date[$i]."- 3 days")), $kits[$currKitID]))
				{
          if (date("D",strtotime($date[$i]."- 1 days")) == $dayClosed1 || date("D",strtotime($date[$i]."- 1 days")) == $dayClosed2)
				  {
						echo "<td class='box reserved'>&nbsp;";
						//echo "<font color='red'>UserID</font>";
						echo "</td>\n";
					} else {
						echo "<td class='box open'>";
						echo "<a class='link' href='reserve.php?KitID=$currKitID&StudentID=$sid&date=$date[$i]'></a></td>\n";
					}
				}
				elseif (isset($checked))
				{
  				$d1 = strtotime($checked[$currKitID][0]);
  				$d2 = strtotime($checked[$currKitID][1]);
					if (strtotime($date[$i]) >= $d1 && strtotime($date[$i]) <= $d2) {
						echo "<td class='box checked'>&nbsp;";
						//echo "<font color='yellow'>UserID</font>";
						echo "</td>\n";
					} else {
						echo "<td class='box open'>";
						echo "<a class='link' href='reserve.php?KitID=$currKitID&StudentID=$sid&date=$date[$i]'></a></td>\n";
					}
				} else {
					echo "<td class='box open'>";
					echo "<a class='link' href='reserve.php?KitID=$currKitID&StudentID=$sid&date=$date[$i]'></a></td>\n";
				}
			} else {
        echo "<td class='box open'>";
				echo "<a class='link' href='reserve.php?KitID=$currKitID&StudentID=$sid&date=$date[$i]'></a></td>\n";
			}
		} ?>
  </tr>
  <?php } ?>
</table>
</div>
<?
include('includes/footer.html');
//print_r($row_kitReserved);
?>