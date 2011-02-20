<?php

//variables
$filter = $_REQUEST['filter'];
if (isset($filter)) {
} else {
	$filter = "no";
}

$StudentID = $_REQUEST['StudentID'];
$SelectedID = $_REQUEST['studentList'];
$filterBy = $_REQUEST['filterList'];
$filterReq = $_REQUEST['txtFilter'];
if (!isset($filterReq)) { 
	$txtVal = "Filter Keyword"; 
		} else { 
			$txtVal = "$filterReq";
		}
if (isset($StudentID)) {
	$filter = "add";
}
//global javascript
?>

<script type="text/javascript">
<?php if ($filter == "add") { ?>
window.onload=initialize;

function initialize() {
	try {
		$("txtFirstName1").addEventListener ("change", chgHandler, true);
		$("txtLastName1").addEventListener ("change", chgHandler, true);
	}
	catch(e) {
		alert("Exception caught: " + e)
	}
}
function chgHandler() {
	var f = $('txtFirstName1').value.substr(0, 1);
	var l = $('txtLastName1').value;
	var nm1 = f+l;
	var nm2 = nm1.replace(/-/g,'').replace(/\s/g,'');
	var nm = nm2.toLowerCase();
	$('txtUser1').value= nm;
} 
<? } ?>
var minLength = "<?php echo $minLength; ?>";
function showResponse(req){
	setTimeout('refreshPage();',1000);
}
function refreshPage(){
	$('filter').value = "no";
	var location = "admin.php?page=students";
	window.location.href = location;
}
function TxtBoxStart(){
	$('txtFilter').disabled = false;
	$('txtFilter').select();
	$('btn').disabled = false;
}
function ChangeValue(){
	$('filter').value = "yes";
	$('form1').submit();
}
function submitForm(){
	$('SelectedID').value = $('studentList').value;
	$('filter').value = "yesyes";
	$('form1').submit();
}
function showAddForm(){
	$('filter').value = "add";
	$('form1').submit();
}
function disableEnterKey(e){
     var key;
     if(window.event)
          key = window.event.keyCode;     //IE
     else
          key = e.which;     //firefox
     if(key == 13)
          return false;
     else
          return true;
}
function isNumeric(sText)
{
   var ValidChars = "0123456789.";
   var IsNumber=true;
   var Char;

   for (i = 0; i < sText.length && IsNumber == true; i++) 
      { 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) 
         {
         IsNumber = false;
         }
      }
   return IsNumber;
   }
function IsEmpty(obj) {
	for(var prop in obj) {
		if(obj.hasOwnProperty(prop))
			return false;
	}
	return true;
}
function Add() {
{
	var StudentID = $('txtStudentID1');
	var txtFirstName1 = $('txtFirstName1');
	var txtLastName1 = $('txtLastName1');
	var txtEmail1 = $('txtEmail1');
	var txtPhone1 = $('txtPhone1');
	var txtContract1 = $('txtContract1');
	var txtUser1 = $('txtUser1');

<?php if (!$sid_as_uid)	{ ?>
   if (!(isNumeric(StudentID.value))) 
   { 
      alert('Only enter the '+minLength+'-digit Student ID number.') 
      StudentID.focus(); 
      StudentID.select(); 
	  return false;
   } 
<?php } ?>

  if (StudentID.value.length < minLength)
   { 
      alert('Must be at least '+minLength+' digits.') 
      StudentID.focus(); 
      StudentID.select(); 
	  return false;
   }
   if (IsEmpty(StudentID.value)) 
   { 
      alert('You have not entered a Student ID number.') 
      StudentID.focus(); 
	  return false;
   } 
   if(IsEmpty(txtFirstName1.value)) 
   { 
      alert('You have not entered a First Name.') 
      txtFirstName1.focus(); 
	  return false;
   } 
   if(IsEmpty(txtLastName1.value)) 
   { 
      alert('You have not entered a Last Name.') 
      txtLastName1.focus(); 
	  return false;
   } 
   if(IsEmpty(txtEmail1.value)) 
   { 
      alert('You have not entered a valid email address.') 
      txtEmail1.focus(); 
	  return false;
   } 
   if (!isNumeric(txtPhone1.value))
   { 
      alert('Only enter the 10-digit phone number.') 
      txtPhone1.focus(); 
      txtPhone1.select(); 
	  return false;
   }
   if (txtPhone1.value.length < 10)
   { 
      alert('Must be a 10-digit phone number.') 
      txtPhone1.focus(); 
      txtPhone1.select(); 
	  return false;
   }
   if(IsEmpty(txtPhone1.value)) 
   { 
      alert('You have not entered a phone number.') 
      txtPhone1.focus(); 
      return false; 
   } 
   if(IsEmpty(txtUser1.value)) 
   { 
      alert('You have not entered a User ID.') 
      txtUser1.focus(); 
      return false; 
   } 
   if(txtContract1.checked == false)
   { 
      alert('You must check this box if you have collected a Lending Contract.') 
      txtContract1.focus(); 
      return false; 
   } 
	new Ajax.Request("add-student.php", 
		{ 
		method: 'post', 
		parameters: $('form3').serialize(true),
		onComplete: showResponse 
		});
	$('alert').style.visibility = "visible";
	$('alert').innerHTML = "Student Record Added";
	setTimeout("window.location.href = '../studentinfo.php?StudentID=' + $('txtStudentID1').value;",1000);
	}
}
function Modify() {
	var txtPhone = $('txtPhone');
   if (!isNumeric(txtPhone.value))
   { 
      alert('Only enter the 10-digit phone number.') 
      txtPhone1.focus(); 
      txtPhone1.select(); 
	  return false;
   }
   if (txtPhone.value.length < 10)
   { 
      alert('Must be a 10-digit phone number.') 
      txtPhone1.focus(); 
      txtPhone1.select(); 
	  return false;
   }
	new Ajax.Request("modify-student.php", 
		{ 
		method: 'post', 
		parameters: $('form2').serialize(true),
		onComplete: showResponse 
		});
	$('alert').style.visibility = "visible";
	$('alert').innerHTML = "Student Record Modified";
	setTimeout("window.location.href = '../studentinfo.php?StudentID=' + $('txtStudentID').value;",1000);
	}
function delEntry(){
	new Ajax.Request("remove-student.php", 
		{ 
		method: 'post', 
		parameters: $('form2').serialize(true),
		onComplete: showResponse 
		});
	$('alert').style.visibility = "visible";
	$('alert').innerHTML = "Student Record Removed";
	$('form2').submit();
}

</script>		

<form id="form1" name="form1" action="admin.php?page=students" method="post">
	<p><strong style="line-height: 30px;">Filter by: </strong>
	<select id="filterList" name="filterList" style="margin-left: 10px; margin-right: 5px;" onChange="TxtBoxStart();">
		<option <?php if (!isset($filterBy)) { echo "selected"; } ?> value="">Select...</option>
		<option <?php if ($filterBy == "StudentID") { echo "selected"; } ?> value="StudentID">Student ID</option>
		<option <?php if ($filterBy == "FirstName") { echo "selected"; } ?> value="FirstName">First Name</option>
		<option <?php if ($filterBy == "LastName") { echo "selected"; } ?> value="LastName">Last Name</option>
	</select>
	<input type="textarea" id="txtFilter" name="txtFilter"style="margin-right: 5px;"  value="<?php echo $txtVal; ?>" disabled="true" onKeyPress="return disableEnterKey(event)"/>
	<input id="btn" disabled="true" type="button" style="margin-right: 35px;" onClick="ChangeValue();" value="Filter" onKeyPress="ChangeValue();" />

	</p><hr/ style="border: 0px; height: 3px; background-color: #ffcc00;">
	<strong style="line-height: 30px;">Student: </strong>
	
	<input id="filter" name="filter" type="hidden" value="<?php echo $filter; ?>" />
	<input id="SelectedID" name="SelectedID" type="hidden" value="<?php echo $SelectedID; ?>" />
	<select id="studentList" name="studentList" style="float: right; width: 300px; height: 25px; margin-right: 35px; margin-top: 5px;" onChange="submitForm();">
		<option selected value="">Select a student...</option>

<?php

//***** DATABASE *****
mysql_select_db($database_equip, $equip);

if ($filter == "no") {
	$query_S = "SELECT * FROM students ORDER BY students.LastName ASC";
} else {
	if ($filter == "yes") {
		$query_S = "SELECT * FROM students WHERE $filterBy LIKE '%$filterReq%' ORDER BY students.LastName ASC";
	} else {
		if ($filter == "yesyes") {
			$query_S = "SELECT * FROM students WHERE ID = '$SelectedID'";
		} else {
			if ($filter == "add") {
				$query_S = "SELECT * FROM students ORDER BY students.LastName ASC";
			}
		}
	}
}

$Students = mysql_query($query_S, $equip) or die(mysql_error());
$row_Students = mysql_fetch_assoc($Students);
$totalRows_Students = mysql_num_rows($Students);

//***** LOOP SELECT BOX *****
mysql_data_seek($Students,0);
while($loop_Students = mysql_fetch_assoc($Students)) {

?>
		<option value="<?php echo $loop_Students['ID']; ?>"><?php echo $loop_Students['LastName']. ", " .$loop_Students['FirstName']. " - " .$loop_Students['StudentID']; ?></option>
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
<br>
<?php
if ($filter == "yesyes") {
?>
<div id="showModify">
<form id="form2" name="form2" action="#" method="post">
<strong>Student ID: </strong><input type="textarea" id="txtStudentID" name="txtStudentID" value="<?php echo $row_Students['StudentID']; ?>" readonly="true" onClick="this.readOnly = false; this.style.background = '#ffffff'; this.style.color = '#000000';" onKeyPress="return disableEnterKey(event)"/>
<br />
<strong>First Name: </strong><input type="textarea" id="txtFirstName" name="txtFirstName" value="<?php echo $row_Students['FirstName']; ?>" readonly="true" onClick="this.readOnly = false; this.style.background = '#ffffff'; this.style.color = '#000000';" onKeyPress="return disableEnterKey(event)"/>
<br />
<strong>Last Name: </strong><input type="textarea" id="txtLastName" name="txtLastName" value="<?php echo $row_Students['LastName']; ?>" readonly="true" onClick="this.readOnly = false; this.style.background = '#ffffff'; this.style.color = '#000000';" onKeyPress="return disableEnterKey(event)"/>
<br />
<strong>Email Address: </strong><input type="textarea" id="txtEmail" name="txtEmail" value="<?php echo $row_Students['Email']; ?>" readonly="true" onClick="this.readOnly = false; this.style.background = '#ffffff'; this.style.color = '#000000';" onKeyPress="return disableEnterKey(event)"/>
<br />
<strong>Phone Number: </strong><input type="textarea" id="txtPhone" name="txtPhone" value="<?php echo $row_Students['Phone']; ?>" readonly="true" onClick="this.readOnly = false; this.style.background = '#ffffff'; this.style.color = '#000000';" onKeyPress="return disableEnterKey(event)"/>
<br />
<strong>User ID: </strong><input type="textarea" id="txtUser" name="txtUser" value="<?php echo $row_Students['UID']; ?>" readonly="true" onClick="this.readOnly = false; this.style.background = '#ffffff'; this.style.color = '#000000';" onKeyPress="return disableEnterKey(event)"/>
<br />
<br />
<a href="#" style="float: right; margin-right: 35px;" onClick="Modify()">
	<img src="<?php echo $root; ?>/images/modify-button.png" border="0" title="Modify" /></a>
<a href="#" style="float: right; margin-right: 10px;" onClick="refreshPage()">
	<img src="<?php echo $root; ?>/images/cancel-button.png" border="0" title="Cancel" /></a>
<a href="#" style="float: right; margin-right: 10px;" onClick="answer=confirm('Do you wish to remove <?php echo $row_Students["FirstName"]; ?> <?php echo $row_Students["LastName"]; ?> from the student records?');if(answer!=0){delEntry();}else{alert('Canceled');setTimeout('refreshPage();',500);}">
	<img src="<?php echo $root; ?>/images/remove-button.png" border="0" title="Remove" /></a>
<input id="pID" name="pID" type="hidden" value="<?php echo $row_Students['ID']; ?>" />
</form>
</div>
<?php
} else {
if ($filter == "add") {
?>
<div id="showAdd">
<form id="form3" name="form3" action="#" method="post">
<strong>Student ID: </strong><input type="textarea" id="txtStudentID1" name="txtStudentID1" value="<?php if (isset($StudentID)) { echo $StudentID; } else echo ""; ?>" onKeyPress="return disableEnterKey(event)" />
<br />
<strong>First Name: </strong><input type="textarea" id="txtFirstName1" name="txtFirstName1" value="" onKeyPress="return disableEnterKey(event)" />
<br />
<strong>Last Name: </strong><input type="textarea" id="txtLastName1" name="txtLastName1" value="" onKeyPress="return disableEnterKey(event)" />
<br />
<strong>Email Address: </strong><input type="textarea" id="txtEmail1" name="txtEmail1" value="" onKeyPress="return disableEnterKey(event)" />
<br />
<strong>Phone Number: </strong><input type="textarea" id="txtPhone1" name="txtPhone1" value="" onKeyPress="return disableEnterKey(event)" />
<br />
<strong>User ID: </strong><input type="textarea" id="txtUser1" name="txtUser1" value="" onKeyPress="return disableEnterKey(event)" />
<br />
<strong>Contract Signed: </strong><input type="checkbox" id="txtContract1" name="txtContract1" value="1" onClick="alert('Have you collected the signed contract?');" />
<br />
<a href="#" style="float: right; margin-right: 35px;" onClick="Add()">
	<img src="<?php echo $root; ?>/images/add-button.png" border="0" title="Add" /></a>
<a href="#" style="float: right; margin-right: 10px;" onClick="refreshPage()">
	<img src="<?php echo $root; ?>/images/cancel-button.png" border="0" title="Cancel" /></a>
</form>
</div>
<?php
}}
?>

<div style="position: absolute; bottom: 10px; left: 20px;"><a href="#" title="Add Student" onClick="showAddForm();"><img src="<?php echo $root; ?>/images/add-student-button.png" border="0" /></a></div>
<?php
mysql_free_result($Students);
?>
