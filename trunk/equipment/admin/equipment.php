<?php

//variables
$EquipmentID = $_REQUEST['equipmentList'];
$hideButtons = false;

if ($_REQUEST['addEquipment'] == "true") {
	$addEquipment = true;
    $hideButtons = true;
}
if ($_REQUEST['addAccessory'] == "true") {
	$addAccessory = true;
    $hideButtons = true;
}
$filter = $_REQUEST['filter'];
if (!isset($filter)) {
	$filter = "no";
}
$filterBy = $_REQUEST['filterList'];
if ($filterBy  == "no") { 
	$filter = "no";
		}
//global javascript
?>

<script type="text/javascript">
function Filter(){
	$('filter').value = "yes";
//	alert('Submit Called');
	submitForm1();
}
function submitForm1() {
	$('form1').submit();
}
function showAddEquipment() {
	$('addEquipment').value = "true";
	submitForm1();
}
function showAddAccessory() {
	$('addAccessory').value = "true";
	submitForm1();
}
function refreshPage(){
	$('filter').value = "no";
	var location = "admin.php?page=equipment"
	window.location.href = location;
}
function showUpImage(){
	if ($('upImage').style.display == "none"){
		$('upImage').style.display = "block";
//		$('upImage').style.height = "35px";
	} else if ($('upImage').style.display == "block") {
		$('upImage').style.display = "none";
//		$('upImage').style.height = "0";
	}
}
function showUpThumb(){
	if ($('upThumb').style.display == "none"){
		$('upThumb').style.display = "block";
//		$('upThumb').style.height = "35px";
	} else if ($('upThumb').style.display == "block") {
		$('upThumb').style.display = "none";
//		$('upThumb').style.height = "0";
	}
}
</script>

<form id="form1" name="form1" action="admin.php?page=equipment" method="post">
	<p><strong style="line-height: 30px;">Genre: </strong>
	<select id="filterList" name="filterList" style="margin-left: 38px; width: 200px; height: 25px;" onChange="Filter();">
		<option <?php if (!isset($filterBy)) { echo "selected"; } ?> value="no">Select a type...</option>
		<option <?php if ($filterBy == "Equipment") { echo "selected"; } ?> value="Equipment">Equipment</option>
		<option <?php if ($filterBy == "Graphic Tablet") { echo "selected"; } ?> value="Graphic Tablet">Graphic Tablet</option>
		<option <?php if ($filterBy == "Scanner") { echo "selected"; } ?> value="Scanner">Scanner</option>
		<option <?php if ($filterBy == "Still Camera") { echo "selected"; } ?> value="Still Camera">Still Camera</option>
		<option <?php if ($filterBy == "Tripod") { echo "selected"; } ?> value="Tripod">Tripod</option>
		<option <?php if ($filterBy == "Video Camera") { echo "selected"; } ?> value="Video Camera">Video Camera</option>
	</select>
	<input id="btn" type="button" style="margin-left: 30px; height: 25px; width: 65px;" onClick="Filter();" value="Filter" onKeyPress="Filter();" />
	</p><hr/ style="border: 0px; height: 3px; background-color: #ffcc00;">
	<strong style="line-height: 30px;">Equipment: </strong>
	
	<input type="hidden" name="addEquipment" id="addEquipment" value="">
	<input type="hidden" name="addAccessory" id="addAccessory" value="">
	<input id="filter" name="filter" type="hidden" value="<?php echo $filter; ?>" />
	<select name="equipmentList" id="equipmentList" style="float: right; width: 300px; height: 25px; margin-right: 35px; margin-top: 5px;" onChange="submitForm1();">
		<option selected value="">Select the equipment...</option>

<?php

//***** DATABASE *****
mysql_select_db($database_equip, $equip);

if ($filter == "no") {
	$query_E = "SELECT * FROM kit ORDER BY kit.Name ASC";
} else {
	if ($filter == "yes") {
		$query_E = "SELECT * FROM kit WHERE kit.Genre = \"$filterBy\" ORDER BY kit.Name ASC";
	}
}

$Equipment = mysql_query($query_E, $equip) or die(mysql_error());
$row_Equipment = mysql_fetch_assoc($Equipment);
$totalRows_Equipment = mysql_num_rows($Equipment);

//***** LOOP SELECT BOX *****
mysql_data_seek($Equipment,0);
while($loop_Equipment = mysql_fetch_assoc($Equipment)) {

?>
		<option value="<?php echo $loop_Equipment['ID']; ?>"><?php echo $loop_Equipment['Name']. ", " .$loop_Equipment['Genre']; ?></option>
<?php 
} 
?>
  </select>
</form>
	<hr/ style="border: 0px; height: 3px; background-color: #ffcc00;">

<?php 

//***** RECORD FORM *****
//	echo $echo1;

?>
<?php
if (empty($EquipmentID)) { if (!$hideButtons) { ?>
<div style="margin-left: 75px; margin-top: 75px;">
<a href="#" title="Add Equipment" onClick="showAddEquipment();"><img src="<?php echo $root; ?>/images/btn-add-equipment.png" border="0" /></a>
<a href="#" title="Add Accessories" onClick="showAddAccessory();"><img src="<?php echo $root; ?>/images/btn-add-accessories.png" border="0" /></a>
</div>
<? }} else { 

//***** DATABASE *****
mysql_select_db($database_equip, $equip);
$equipModQuery = "SELECT * FROM kit WHERE kit.ID = \"$EquipmentID\"";
$equipMod = mysql_query($equipModQuery, $equip) or die(mysql_error());
$row_equipMod = mysql_fetch_assoc($equipMod);
$totalRows_equipMod = mysql_num_rows($equipMod);

?>
<div id="showModEquip">
<h2>Modify Equipment</h2>
<form id="frmModEquip" name="frmModEquip" enctype="multipart/form-data" action="actions-equipment.php" method="POST">
	<input type="hidden" name="EquipmentID" value="<? echo $EquipmentID; ?>" />
	<label>Name: </label><input id="tb" type="text" name="txtName" value="<?php echo $row_equipMod['Name']; ?>" /><br>
	<label>Image: </label><input id="tb" type="text" value="<?php echo $row_equipMod['Image']; ?>" /> <a href="#up1" name="up1" onclick="showUpImage();">Upload Image</a><br>
    <span id="upImage" style="display: none; "><input id="tb" type="file" name="upImage" /> <a href="#up1" onclick="showUpImage();">Cancel</a><br></span>
	<label>Genre: </label><input id="tb" type="text" name="txtGenre" value="<?php echo $row_equipMod['Genre']; ?>" /><br>
	<label>Hours: </label><input id="tb" type="text" name="txtHours" value="<?php echo $row_equipMod['CheckHours']; ?>" /><br>
	<label>Serial No: </label><input id="tb" type="text" name="txtSerial" value="<?php echo $row_equipMod['SerialNumber']; ?>" /><br>
	<label>Model No: </label><input id="tb" type="text" name="txtModel" value="<?php echo $row_equipMod['ModelNumber']; ?>" /><br>
	<label>Thumb: </label><input id="tb" type="text" value="<?php echo $row_equipMod['ImageThumb']; ?>" /> <a href="#up2" name="up2" onclick="showUpThumb();">Upload Thumbnail</a><br>
    <span id="upThumb" style="display: none; "><input id="tb" type="file" name="upThumb" /> <a href="#up2" onclick="showUpThumb();">Cancel</a><br></span>
	<strong>Needs Repair: </strong><input id="chk" type="checkbox" name="chkRepair" value="checkbox" <?php if ($row_equipMod['Repair'] == 1) { echo "checked='yes'"; } ?> />
	<strong>Special Contract: </strong><input id="chk" type="checkbox" name="chkContract" value="checkbox" <?php if ($row_equipMod['ContractRequired'] == 1) { echo "checked='yes'"; } ?> /><br>
	<label>Notes: </label><br/><textarea cols="50" rows="5" name="txtNotes"><?php echo $row_equipMod['Notes']; ?></textarea>
	<input type="hidden" name="MAX_FILE_SIZE" value="500" />
	<br />
	<a href="#" style="float: right; margin-right: 35px;" onClick="Modify()">
		<img src="<?php echo $root; ?>/images/modify-button.png" border="0" title="Modify" /></a>
	<a href="#" style="float: right; margin-right: 10px;" onClick="refreshPage()">
		<img src="<?php echo $root; ?>/images/cancel-button.png" border="0" title="Cancel" /></a>
	<a href="#" style="float: right; margin-right: 10px;" onClick="answer=confirm('Do you wish to remove this record?');if(answer!=0){delEntry();}else{alert('Canceled')}">
		<img src="<?php echo $root; ?>/images/remove-button.png" border="0" title="Remove" /></a>
</form>
</div>
<?
}
if ($addEquipment) { ?>
<div id="showAddEquip">
<h2>Add Equipment</h2>
<form id="frmAddEquip" name="frmAddEquip" enctype="multipart/form-data" action="actions-equipment.php" method="POST">
	<input type="hidden" name="EquipmentID" value="" />
	<label>Name: </label><input id="tb" type="text" name="txtName" value="" /><br>
	<label>Image: </label><input id="tb" type="text" disabled="disabled" /> <a href="#up1" name="up1" onclick="showUpImage();">Upload Image</a><br>
    <span id="upImage" style="display: none; "><input id="tb" type="file" name="upImage" /> <a href="#up1" onclick="showUpImage();">Cancel</a><br></span>
	<label>Genre: </label><input id="tb" type="text" name="txtGenre" value="" /><br>
	<label>Hours: </label><input id="tb" type="text" name="txtHours" value="" /><br>
	<label>Serial No: </label><input id="tb" type="text" name="txtSerial" value="" /><br>
	<label>Model No: </label><input id="tb" type="text" name="txtModel" value="" /><br>
	<label>Thumb: </label><input id="tb" type="text" disabled="disabled" /> <a href="#up2" name="up2" onclick="showUpThumb();">Upload Thumbnail</a><br>
    <span id="upThumb" style="display: none; "><input id="tb" type="file" name="upThumb" /> <a href="#up2" onclick="showUpThumb();">Cancel</a><br></span>
	<strong>Needs Repair: </strong><input id="chk" type="checkbox" name="chkRepair" value="checkbox" />
	<strong>Special Contract: </strong><input id="chk" type="checkbox" name="chkContract" value="checkbox" /><br>
	<label>Notes: </label><br/><textarea cols="50" rows="5" name="txtNotes"></textarea>
	<input type="hidden" name="MAX_FILE_SIZE" value="500" />
	<br />
	<a href="#" style="float: right; margin-right: 35px;" onClick="Add()">
		<img src="<?php echo $root; ?>/images/add-button.png" border="0" title="Add" /></a>
	<a href="#" style="float: right; margin-right: 10px;" onClick="refreshPage()">
		<img src="<?php echo $root; ?>/images/cancel-button.png" border="0" title="Cancel" /></a>
</form>
</div>
<?php
}
if ($addAccessory) { ?>
<div id="showAddAccessory">
<h2>Add Accessory</h2>
<form id="frmAddAccessory" name="frmAddAccessory" enctype="multipart/form-data" action="actions-equipment.php" method="POST">
	<input type="hidden" name="EquipmentID" value="" />
	<label>Name: </label><input id="tb" type="text" name="txtName" value="" /><br>
	<label>Image: </label><input id="tb" type="text" disabled="disabled" /> <a href="#up1" name="up1" onclick="showUpImage();">Upload Image</a><br>
    <span id="upImage" style="display: none; "><input id="tb" type="file" name="upImage" /> <a href="#up1" onclick="showUpImage();">Cancel</a><br></span>
	<label>Genre: </label><input id="tb" type="text" name="txtGenre" value="" /><br>
	<label>Hours: </label><input id="tb" type="text" name="txtHours" value="" /><br>
	<label>Serial No: </label><input id="tb" type="text" name="txtSerial" value="" /><br>
	<label>Model No: </label><input id="tb" type="text" name="txtModel" value="" /><br>
	<label>Thumb: </label><input id="tb" type="text" disabled="disabled" /> <a href="#up2" name="up2" onclick="showUpThumb();">Upload Thumbnail</a><br>
    <span id="upThumb" style="display: none; "><input id="tb" type="file" name="upThumb" /> <a href="#up2" onclick="showUpThumb();">Cancel</a><br></span>
	<strong>Needs Repair: </strong><input id="chk" type="checkbox" name="chkRepair" value="checkbox" />
	<strong>Special Contract: </strong><input id="chk" type="checkbox" name="chkContract" value="checkbox" /><br>
	<label>Notes: </label><br/><textarea cols="50" rows="5" name="txtNotes"></textarea>
	<input type="hidden" name="MAX_FILE_SIZE" value="500" />
	<br />
	<a href="#" style="float: right; margin-right: 35px;" onClick="Add()">
		<img src="<?php echo $root; ?>/images/add-button.png" border="0" title="Add" /></a>
	<a href="#" style="float: right; margin-right: 10px;" onClick="refreshPage()">
		<img src="<?php echo $root; ?>/images/cancel-button.png" border="0" title="Cancel" /></a>
</form>
</div>
<?php
}

mysql_free_result($Equipment);
?>