<?php
require_once('config.php'); 

function show_login() {
	//SHOW LOGIN FORM
	global $root;
	
	echo "<div style='margin-left: auto; margin-right: auto; text-align: left; width: 300px;'>";
	echo "<p><strong>Enter your user name and password to login.</strong></p>";
	echo "<form name='form' action='$PHP_SELF' method='post'>";
	echo "<p>Username: <input style='margin-left: 7.5px;' name='Username' type='text' id='Username'></p>";
	echo "<p>Password: <input style='margin-left: 10px;' name='Password' type='password' id='Password'></p>";
	echo "<p><input type='submit' name='Submit' value='Login'></p>";
	echo "</form></div>";
	
	include('includes/footer.html'); 
}

if ($ldap_enabled) {
	include('includes/ldap/defaultincludes.inc'); 
	include('includes/heading2.html'); 
	printLoginForm($TargetURL);
	include('includes/footer.html'); 
} 
else 
{

$Username = $_REQUEST['Username'];
$Password = $_REQUEST['Password'];

// encrypt password
$encrypted_mypassword=md5($Password);

//CHECK USERNAME AND PASSWORD
if (isset($Username)) {

	if (empty($Password)) {
		include('includes/heading2.html');
		echo "<p class='alert' align='center'>Invalid Password</p>";
		show_login();
	} else {

		mysql_select_db($database_equip, $equip);
		$query_Recordset1 = sprintf("SELECT * FROM users WHERE Username = '$Username'");
		$Recordset1 = mysql_query($query_Recordset1, $equip) or die(mysql_error());
		$row_Recordset1 = mysql_fetch_assoc($Recordset1);
		$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	
		if ($row_Recordset1['Password'] == $encrypted_mypassword) { 
			//name & start the session then register a variable
			session_name("EquipmentCheckout");
			session_start();
			session_register($Username);
			// this sets variables in the session
			$_SESSION['user'] = $Username;
			$_SESSION['auth'] = true;
			$_SESSION['time'] = time();
			mysql_free_result($Recordset1);
			echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php\">";
		} else {
			include('includes/heading2.html');
			echo "<p class='alert' align='center'>Invalid Username or Password</p>";
			show_login();
		} 
	}
} else {
	include('includes/heading2.html');
	show_login();
	} 
} 
?>

