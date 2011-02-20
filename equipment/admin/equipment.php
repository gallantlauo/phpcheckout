<?php
//move to config
$checkHours = false;
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

<script type="text/javascript" src="../includes/effects.js"></script>

<script type="text/javascript">
function Filter(){
	$('filter').value = "yes";
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
//	$('filter').value = "no";
	var location = "admin.php?page=equipment"
	window.location.href = location;
}
function startUpload(){
	$('f1_upload_process').style.visibility = 'visible';
	return true;
}
function stopUpload(success){
	var result = '';
	if (success == 1){
		$('result').innerHTML = '<span class="alert">The file was uploaded successfully!<\/span><br/><br/>';
	} else {
		$('result').innerHTML = '<span class="alert">There was an error during file upload!<\/span><br/><br/>';
	}
	$('f1_upload_process').style.visibility = 'hidden';
	return true;
}
function modTxt(name){
	var box = "txt"+finishedType;
	var upBox = "up"+finishedType;
	var newOpt = document.createElement("option");
	newOpt.value = name;
	newOpt.text = name;
	$(box).add(newOpt, null);
	var num = $(box).length;
	$(box).selectedIndex = num-1;
	slideDiv(upBox);
}
function uploadImage(type){
	finishedType = type;
	startUpload();
	$('modEquip').value = "img";
	$('form2').target = "upload_target";
	$('form2').submit();
}
function IsEmpty(obj) {
	for(var prop in obj) {
		if(obj.hasOwnProperty(prop))
			return false;
	}
	return true;
}
function showResponse(req){
	setTimeout('refreshPage();',1000);
}
function checkForm(){
   var name = $F('txtName');
   var image = $F('txtImage');
   var genre = $F('txtGenre');
   var thumb = $F('txtThumb');
   // var selIndex = thumb.selectedIndex;
   if (IsEmpty(name) || IsEmpty(image) || IsEmpty(genre) || IsEmpty(thumb))
   // || IsEmpty(thumb.options[selIndex].value)
   {
	  $$('label.im').invoke('setStyle', { color: 'red' });
	  return false;
   } else { return true; }
}
function doAjaxSubmit(page,form){
	new Ajax.Request(page,
		{
		method: 'post',
		parameters: $(form).serialize(true),
		onComplete: submitForm1 
		});	
	$('alert').style.visibility = "visible";
}
function addOrModify(which){
	if (checkForm() == false) { return false; }
	$('modEquip').value = which;
	doAjaxSubmit('equipment-form.php','form2');
	$('alert').innerHTML = "Equipment Kit "+ which;
}
function delKit(which){
	$('modEquip').value = which;
	doAjaxSubmit('equipment-form.php','form2');
	$('alert').innerHTML = "Kit Deleted";
}
function AddAccessory(which){
	$('modEquip').value = which;
	doAjaxSubmit('add-accessory.php','form2');
	$('alert').innerHTML = "Accessory Added";
}
function addClassAction(){
	doAjaxSubmit('add-class.php','form3');
	$('alert').style.visibility = "visible";
	$('alert').innerHTML = "Class Added";
}
function addAccAction(){
	doAjaxSubmit('add-accessory.php','form4');
	$('alert').style.visibility = "visible";
	$('alert').innerHTML = "Accessory Added";
}
function delClass(numero){
    $('rkClassID').value = numero;
	doAjaxSubmit('remove-class.php','form3');
	$('alert').innerHTML = "Class Removed";
}
function delAcc(numero){
    $('AccessorytypeID').value = numero;
	doAjaxSubmit('remove-accessory.php','form4');
	$('alert').innerHTML = "Accessory Removed";
}
function slideDiv(e){
    Effect.toggle(e, 'Blind', {duration:1});
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
		<option <?php if (!isset($EquipmentID)) { echo "selected"; } ?> value="">Select the equipment...</option>
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
while($loop_Equipment = mysql_fetch_assoc($Equipment)) { ?>
<option <?php if ($EquipmentID == $loop_Equipment['ID']) { echo "selected"; } ?> value="<?php echo $loop_Equipment['ID']; ?>"><?php echo $loop_Equipment['Name']. ", " .$loop_Equipment['Genre']; ?></option>
<?php } ?>
	  </select>
</form>
	<hr/ style="border: 0px; height: 3px; background-color: #ffcc00;">
<?php

//***** RECORD FORM *****
if (empty($EquipmentID)) { if (!$hideButtons) { ?>
<div style="margin-left: auto; margin-right: auto; margin-top: 75px; width: 265px;">
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
<form id="form2" name="form2" method="post" enctype="multipart/form-data" action="equipment-form.php">
	<input type="hidden" name="EquipmentID" value="<? echo $EquipmentID; ?>" />
	<label class="lb im">Name: </label><input name="txtName" id="txtName" class="tb" type="text" value="<?php echo $row_equipMod['Name']; ?>" /><br>
	<label class="lb im">Image: </label>
	<select name="txtImage" id="txtImage" class="tb" >
<?php

//***** DATABASE ***** for image loop
mysql_select_db($database_equip, $equip);
$imageQuery = "SELECT DISTINCT Image FROM kit ORDER BY Image";

$Image = mysql_query($imageQuery, $equip) or die(mysql_error());
$totalRows_Image = mysql_num_rows($Image);
if ($totalRows_Image < 1) {
	echo "<option selected  value='large-default.jpg'>large-default.jpg</option>";
} else {

//***** LOOP SELECT BOX ***** for image
mysql_data_seek($Image,0);
while($loop_Image = mysql_fetch_assoc($Image)) { ?>
    <option <?php if ($row_equipMod['Image'] == $loop_Image['Image']) { echo "selected";} ?>  value="<?php echo $loop_Image['Image']; ?>"><?php echo $loop_Image['Image']; ?></option>
<?php }} ?>
    </select> <a href="#" onclick="slideDiv('upImage');">Upload Image</a><br>
    <div id="upImage" style="display: none; "><input name="upImage" class="tb" type="file" onChange="uploadImage('Image');" /> <a href="#" onclick="slideDiv('upImage');">Cancel</a><br></div>
	<label class="lb im">Genre: </label><input name="txtGenre" id="txtGenre" class="tb" type="text" value="<?php echo $row_equipMod['Genre']; ?>" /><br>
<? if ($checkHours) { ?>
	<label class="lb">Hours: </label><input name="txtHours" id="txtHours" class="tb" type="text" value="<?php echo $row_equipMod['CheckHours']; ?>" /><br>
<? } ?>
	<label class="lb">Serial No: </label><input name="txtSerial" id="txtSerial" class="tb" type="text" value="<?php echo $row_equipMod['SerialNumber']; ?>" /><br>
	<label class="lb">Model No: </label><input name="txtModel" id="txtModel" class="tb" type="text" value="<?php echo $row_equipMod['ModelNumber']; ?>" /><br>
	<label class="lb im">Thumb: </label>
	<select name="txtThumb" id="txtThumb" class="tb" >
<?php
//***** DATABASE ***** for thumb loop
mysql_select_db($database_equip, $equip);
$thumbQuery = "SELECT DISTINCT ImageThumb FROM kit ORDER BY ImageThumb";

$Thumb = mysql_query($thumbQuery, $equip) or die(mysql_error());
$totalRows_Thumb = mysql_num_rows($Thumb);
if ($totalRows_Thumb < 1 )  {
	echo "<option selected  value='thumb-default.jpg'>thumb-default.jpg</option>";
} else {

//***** LOOP SELECT BOX ***** for thumb
mysql_data_seek($Thumb,0);
while($loop_Thumb = mysql_fetch_assoc($Thumb)) { ?>
    <option <?php if ($row_equipMod['ImageThumb'] == $loop_Thumb['ImageThumb']) { echo "selected";} ?>  value="<?php echo $loop_Thumb['ImageThumb']; ?>"><?php echo $loop_Thumb['ImageThumb']; ?></option>
<?php }} ?>
    </select> <a href="#" onclick="slideDiv('upThumb');">Upload Thumbnail</a><br>
    <div id="upThumb" style="display: none; "><input name="upThumb" class="tb" type="file" onChange="uploadImage('Thumb');" /> <a href="#" onclick="slideDiv('upThumb');">Cancel</a><br></div>
	<strong>Needs Repair: </strong><input name="chkRepair" id="chkRepair" class="chk" type="checkbox" value="1" <?php if ($row_equipMod['Repair'] == 1) { echo "checked='yes'"; } ?> />
	<strong>Special Contract: </strong><input name="chkContract" id="chkContract" class="chk" type="checkbox" value="1" <?php if ($row_equipMod['ContractRequired'] == 1) { echo "checked='yes'"; } ?> /><br>
	<label class="lb">Notes: </label><br/><textarea cols="50" rows="5" name="txtNotes" id="txtNotes"><?php echo $row_equipMod['Notes']; ?></textarea>
	<input type="hidden" id="modEquip" name="modEquip" value="mod" />
</form>
	<a href="#aC" name="aC" onClick="slideDiv('assocClass');">View Associated Classes</a><br/>
	<div id="assocClass" style="display: none; border:1px solid #000; padding-left: 15px;">
<strong>Registered Classes:</strong><br>

<?php //show class records for selected kit
    mysql_select_db($database_equip, $equip);
    $query_rc2 = "SELECT kit_class.ID AS kcID, kit_class.KitID, kit_class.ClassID, class.Name FROM kit_class INNER JOIN class ON kit_class.ClassID = class.ID WHERE KitID = '$EquipmentID'";
    $query_Recordset2 = sprintf($query_rc2);
    $Recordset2 = mysql_query($query_Recordset2, $equip) or die(mysql_error());
    $row_Recordset2 = mysql_fetch_assoc($Recordset2);
    $totalRows_Recordset2 = mysql_num_rows($Recordset2);
if (isset($row_Recordset2['ClassID'])) {
do {
	if (empty($ClassIDSQL)){
		$ClassIDSQL = $row_Recordset2['ClassID'];
	} else {
		$ClassIDSQL = $ClassIDSQL . " OR kit_class.ClassID = " . $row_Recordset2['ClassID'] ;
	}
?>
	<a id="remClass" href="#" onClick="answer=confirm('Remove this class?');if(answer!=0){delClass(<? echo $row_Recordset2['kcID']; ?>);}else{alert('Canceled')}" >
	<img id="remClass" src="<?php echo $root; ?>/images/remove_icn.png" border="0" title="Remove Class" /></a>
	<? echo $row_Recordset2['Name']; ?><br/> 
<?php
	}
while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
}
if ($totalRows_Recordset2==0) {
    echo "<span class='alert'>No classes registered. Add from the selection below.</span>";
}
 ?>
  	<!-- ***********add/remove class form*********** -->

    <form id="form3" name="form3" action="classes.php" method="post">
    <input id="rkClassID" name="rkClassID" type="hidden" />
    <select name="class" size="1" id="class" style='margin-left: 20px;'>
<?
  	//***** ADD CLASS FORM *****
	$classes = mysql_query("SELECT * FROM class ORDER BY class.Name") or die(mysql_error());
	while($option = mysql_fetch_array( $classes )) {
    // Print out the contents of each row into an option
    echo "<option value='$option[Name]'>$option[Name]</option>";
    } ?>
    </select>
    <input name="EquipmentID" type="hidden" value="<?php echo $EquipmentID; ?>">
    <input type="button" name="AddClass" value="Add Class" onClick="addClassAction();">
    </form>
	</div>
	<a href="#aA" name="aA" onClick="slideDiv('assocAccess');">View Associated Accessories</a><br/>
	<div id="assocAccess" style="display: none; border:1px solid #000; padding-left: 15px;">
<strong>Registered Accessories:</strong><br>

<?php //show class records for selected kit
    mysql_select_db($database_equip, $equip);
    $query_rc9 = "SELECT kit_accessorytype.ID AS kaID, kit_accessorytype.KitID, kit_accessorytype.AccessorytypeID, accessorytype.Name FROM kit_accessorytype INNER JOIN accessorytype ON kit_accessorytype.AccessorytypeID = accessorytype.ID WHERE KitID = '$EquipmentID'";
    $query_Recordset9 = sprintf($query_rc9);
    $Recordset9 = mysql_query($query_Recordset9, $equip) or die(mysql_error());
    $row_Recordset9 = mysql_fetch_assoc($Recordset9);
    $totalRows_Recordset9 = mysql_num_rows($Recordset9);
if (isset($row_Recordset9['AccessorytypeID'])) {
do {
	if (empty($AccessorytypeIDSQL)){
		$AccessorytypeIDSQL = $row_Recordset9['AccessorytypeID'];
	} else {
		$AccessorytypeIDSQL = $ClassIDSQL . " OR kit_accessorytype.AccessorytypeID = " . $row_Recordset9['AccessorytypeID'] ;
	}
?>
	<a id="remAcc" href="#" onClick="answer=confirm('Remove this accessory?');if(answer!=0){delAcc(<? echo $row_Recordset9['kaID']; ?>);}else{alert('Canceled')}" >
	<img id="remAcc" src="<?php echo $root; ?>/images/remove_icn.png" border="0" title="Remove Accessory" /></a>
	<? echo $row_Recordset9['Name']; ?><br/> 
<?php
	}
while ($row_Recordset9 = mysql_fetch_assoc($Recordset9));
}
if ($totalRows_Recordset9==0 && $EquipmentID != "") {
    echo "<span class='alert'>No Accessories registered. Add from the selection below.</span>";
}
 ?>
  	<!-- ***********add/remove class form*********** -->

    <form id="form4" name="form4" action="add-accessory.php" method="post">
    <input id="AccessorytypeID" name="AccessorytypeID" type="hidden" />
    <select name="accessory" size="1" id="accessory" style='margin-left: 20px;'>
<?
  	//***** ADD CLASS FORM *****
	$accessories = mysql_query("SELECT * FROM accessorytype ORDER BY accessorytype.Name") or die(mysql_error());
	while($option = mysql_fetch_array( $accessories )) {
    // Print out the contents of each row into an option
    echo "<option value='$option[Name]'>$option[Name]</option>";
    } ?>
    </select>
    <input name="EquipmentID" type="hidden" value="<?php echo $EquipmentID; ?>">
    <input type="button" name="AddAcc" value="Add Accessory" onClick="addAccAction();">
    </form>
	</div>
<p id="f1_upload_process">Loading...<br/><img src="../images/loader.gif" /></p>
<p id="result"></p>	<br />
	<a href="#" style="float: right; margin-right: 35px;" onClick="addOrModify('mod');">
		<img src="<?php echo $root; ?>/images/modify-button.png" border="0" title="Modify" /></a>
	<a href="#" style="float: right; margin-right: 10px;" onClick="refreshPage();">
		<img src="<?php echo $root; ?>/images/cancel-button.png" border="0" title="Cancel" /></a>
	<a href="#" style="float: right; margin-right: 10px;" onClick="answer=confirm('Do you wish to remove this kit?');if(answer!=0){delKit('rem');}">
		<img src="<?php echo $root; ?>/images/remove-button.png" border="0" title="Remove" /></a>
<div id="image_details"></div>
<iframe id="upload_target" name="upload_target" src="" style="width:0;height:0;border:0px solid #fff;"></iframe>
</div>
<?
}
if ($addEquipment) { ?>
<div id="showAddEquip">
<h2>Add Equipment</h2>
<form id="form2" name="form2" method="post" enctype="multipart/form-data" action="equipment-form.php">
	<label class="lb im">Name: </label><input name="txtName" id="txtName" class="tb" type="text" value="" /><br>
	<label class="lb im">Image: </label>
	<select name="txtImage" id="txtImage" class="tb" >
<?php

//***** DATABASE ***** for image loop
mysql_select_db($database_equip, $equip);
$imageQuery = "SELECT DISTINCT Image FROM kit ORDER BY Image";

$Image = mysql_query($imageQuery, $equip) or die(mysql_error());
$totalRows_Image = mysql_num_rows($Image);
if ($totalRows_Image < 1) {
	echo "<option selected  value='large-default.jpg'>large-default.jpg</option>";
} else {

//***** LOOP SELECT BOX ***** for image
mysql_data_seek($Image,0);
while($loop_Image = mysql_fetch_assoc($Image)) { ?>
    <option <?php if ($row_equipMod['Image'] == $loop_Image['Image']) { echo "selected";} ?>  value="<?php echo $loop_Image['Image']; ?>"><?php echo $loop_Image['Image']; ?></option>
<?php }} ?>
    </select> <a href="#" onclick="slideDiv('upImage');">Upload Image</a><br>
    <div id="upImage" style="display: none; "><input name="upImage" class="tb" type="file" size="30" onChange="uploadImage('Image');" /> <a href="#" onclick="slideDiv('upImage');">Cancel</a><br></div>
	<label class="lb im">Genre: </label><input name="txtGenre" id="txtGenre" class="tb" type="text" value="" /><br>
<? if ($checkHours) { ?>
	<label class="lb">Hours: </label><input name="txtHours" id="txtHours" class="tb" type="text" value="" /><br>
<? } ?>
	<label class="lb">Serial No: </label><input name="txtSerial" id="txtSerial" class="tb" type="text" value="" /><br>
	<label class="lb">Model No: </label><input name="txtModel" id="txtModel" class="tb" type="text" value="" /><br>
	<label class="lb im">Thumb: </label>
	<select name="txtThumb" id="txtThumb" class="tb" >
<?php
//***** DATABASE ***** for thumb loop
mysql_select_db($database_equip, $equip);
$thumbQuery = "SELECT DISTINCT ImageThumb FROM kit ORDER BY ImageThumb";

$Thumb = mysql_query($thumbQuery, $equip) or die(mysql_error());
$totalRows_Thumb = mysql_num_rows($Thumb);
if ($totalRows_Thumb < 1) {
	echo "<option selected  value='thumb-default.jpg'>thumb-default.jpg</option>";
} else {

//***** LOOP SELECT BOX ***** for thumb
mysql_data_seek($Thumb,0);
while($loop_Thumb = mysql_fetch_assoc($Thumb)) { ?>
    <option <?php if ($row_equipMod['ImageThumb'] == $loop_Thumb['ImageThumb']) { echo "selected";} ?>  value="<?php echo $loop_Thumb['ImageThumb']; ?>"><?php echo $loop_Thumb['ImageThumb']; ?></option>
<?php }} ?>
    </select> <a href="#" onclick="slideDiv('upThumb');">Upload Thumbnail</a><br>
    <div id="upThumb" style="display: none; "><input name="upThumb" class="tb" type="file" size="30" onChange="uploadImage('Thumb');" /> <a href="#" onclick="slideDiv('upThumb');">Cancel</a><br></div>
	<strong>Needs Repair: </strong><input name="chkRepair" id="chkRepair" class="chk" type="checkbox" value="checkbox" />
	<strong>Special Contract: </strong><input name="chkContract" id="chkContract" class="chk" type="checkbox" value="checkbox" /><br>
	<label class="lb">Notes: </label><br/><textarea cols="50" rows="5" name="txtNotes" id="txtNotes"></textarea>
	<input type="hidden" id="modEquip" name="modEquip" value="add" />
<p id="f1_upload_process">Loading...<br/><img src="../images/loader.gif" /></p>
<p id="result"></p>	<br />
	<a href="#" style="float: right; margin-right: 35px;" onClick="addOrModify('add');">
		<img src="<?php echo $root; ?>/images/add-button.png" border="0" title="Add" /></a>
	<a href="#" style="float: right; margin-right: 10px;" onClick="refreshPage();">
		<img src="<?php echo $root; ?>/images/cancel-button.png" border="0" title="Cancel" /></a>
</form>
<div id="image_details"></div>
<iframe id="upload_target" name="upload_target" src="" style="width:0;height:0;border:0px solid #fff;"></iframe>
</div>
<?php
}
if ($addAccessory) { ?>
<div id="showAddAccessory">
<h2>Add Accessory</h2>
<form id="form2" name="form2" method="post" action="equipment-form.php">
	<label class="lb">Name: </label><input name="txtName" id="txtName" class="tb" type="text" value="" /><br>
	<input type="hidden" id="modEquip" name="modEquip" value="acc" />
	<br />
	<a href="#" style="float: right; margin-right: 35px;" onClick="AddAccessory('acc');">
		<img src="<?php echo $root; ?>/images/add-button.png" border="0" title="Add" /></a>
	<a href="#" style="float: right; margin-right: 10px;" onClick="refreshPage();">
		<img src="<?php echo $root; ?>/images/cancel-button.png" border="0" title="Cancel" /></a>
</form>
</div>
<?php
}

mysql_free_result($Equipment);
?>