<?php
require_once('../config.php');

if($_POST){
	//get import id from post then get items from db
	$ImportID = $_POST['data']['ImportID'];
	mysql_select_db($database_equip, $equip);
	$import_query = "SELECT * FROM import WHERE ID = '$ImportID'";
	$import = mysql_query($import_query, $equip) or die(mysql_error());
	$row_imports = mysql_fetch_assoc($import);
	$rowCount = mysql_num_rows($import);
	
	// need to move ldap server and ldap adding info to config
	$ldap_host = "artserverx.arts.ccny.cuny.edu";
	$ds = ldap_connect($ldap_host);
	$bindDn = "uid=ldapadmin,cn=users,dc=artserverx,dc=arts,dc=ccny,dc=cuny,dc=edu";
	$baseDn = "cn=users,dc=artserverx,dc=arts,dc=ccny,dc=cuny,dc=edu";
	$pw = "graph1cs";
	if ($ds) 
	{
	    ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
			
		// bind with appropriate dn to give update access
		$b = ldap_bind($ds, $bindDn, $pw);
		
		//options and constructors
	    $makeGuid = strtoupper(md5($uid));
	    $GUID = substr($makeGuid, 0, 8).
	    "-".substr($makeGuid, 8, 4).
	    "-".substr($makeGuid, 12, 4).
	    "-".substr($makeGuid, 16, 4).
	    "-".substr($makeGuid, 20, 12);
	  	$disk_quota = 2;
	  	$home_share = "edm2010";
	  	$gid_number = 20;
	  	$path = "/Network/Servers/artserverx.arts.ccny.cuny.edu/Volumes/edm/edm2010";
	
		$uid = $_POST['data']['UserID'];
	  	$fn = $_POST['data']['FirstName'];
	  	$ln = $_POST['data']['LastName'];
	  	$both = $fn . " " . $ln;
	
	  	// **approve** insert into ldap and checkout, then remove from import
	
		//artificial search, checking uid redundancy
	  	$r2 = ldap_search($ds, $baseDn, "uid=$uid");
	    $result2 = ldap_get_entries($ds, $r2);
	    $uids = array();
	    for($i=0;$i<$result2["count"];$i++)
	    {
	      $in = $result2[$i]["uid"][0];
	      array_push($uids, $in);
	      sort($uids);
	    }
	  	if (in_array($uid, $uids))
	  	{
	      $uid = $uid."01";
	      if (in_array($uid, $uids))
	      {
	        $uid = $uid."02";
	      }
	    }
	
	  	//artificial search, looking for maxUidNumber
	  	$r1 = ldap_search($ds, $baseDn, "uid=*");
	    $result1 = ldap_get_entries($ds, $r1);
	    $uidNums = array();
	    for($i=0;$i<$result1["count"];$i++)
	    {
	      $last = $result1[$i]["uidnumber"][0];
	      array_push($uidNums, $last);
	      sort($uidNums);
	    }
	  	$maxUid = array_pop($uidNums);
	    $maxUid = $maxUid+1;
	  	
	  	//ldap data
	    $adduser['apple-user-homeurl'] = "<home_dir><url>afp://" . $ldap_host . "/" . $home_share . "</url><path>/$uid</path></home_dir>";
	    $adduser["apple-user-homequota"] = trim(strval(($disk_quota * (1024 * 1024 * 1024)) + 1));
	    $adduser['cn'] = $both;
	    $adduser['objectclass'][0] = 'inetOrgPerson';
	    $adduser['objectclass'][1] = 'posixAccount';
	    $adduser['objectclass'][2] = 'shadowAccount';
	    $adduser['objectclass'][3] = 'apple-user';
	    $adduser['objectclass'][4] = 'extensibleObject';
	    $adduser['objectclass'][5] = 'organizationalPerson';
	    $adduser['objectclass'][6] = 'top';
	    $adduser['objectclass'][7] = 'person';
	    $adduser['givenName'] = $fn;
	    $adduser['sn'] = $ln;
	    $adduser['mail'] = $_POST['data']['Email'];
	    // and now the tricky part, base64 encode the binary hash result: this might not work
	    $adduser['userPassword'] = '{md5}' . base64_encode(pack('H*',md5('1234')));
	    $adduser["uid"] = $uid;
	    $adduser["uidNumber"] = trim(strval($maxUid));
	    $adduser["gidNumber"] = $gid_number;
	    $adduser["homeDirectory"] = $path . "/" . $uid;
	    $adduser["loginShell"] = '/bin/bash';
	    $adduser["apple-generateduid"] = $GUID;
	    //$adduser["apple-mcxflags"] = $data['mcx'];
	    //$adduser["apple-user-printattribute"] = $data['attrib'];
	    $adduser["homePhone"] = $_POST['data']['Phone'];
	    //$adduser["telephoneNumber"] = $_POST['data']['Phone'];
	    //$adduser["emptyfield"] = $_POST['data']['StudentID'];
	  
  	    //add data to ldap
  	    $a = ldap_add($ds, "uid=$uid,$baseDn", $adduser);
  
  	    if ($a)
  	    {
      		$output = shell_exec("dscl -u ldapadmin -P $pw /LDAPv3/127.0.0.1 -passwd /Users/$uid 1234 2>&1 &");
			// dscl command is mac os x server only
      		ldap_close($ds);
      		
      		// now delete from db
			mysql_select_db($database_equip, $equip);
			$delete_query = "DELETE * FROM import WHERE ID = '$ImportID'";
			mysql_query($delete_query, $equip) or die(mysql_error());
			
  			echo "Success";
  	    } 
  	    else 
  	    {
  	    	echo "Add unsuccessful.";
  	    }
	} 
	else 
	{
	    echo "Unable to connect to LDAP server";
	}	

} 
else 
{
	mysql_select_db($database_equip, $equip);
	$import_query = "SELECT * FROM import ORDER BY ID";
	$import = mysql_query($import_query, $equip) or die(mysql_error());
	$row_imports = mysql_fetch_assoc($import);
	$rowCount = mysql_num_rows($import);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Mass Import Form</title>
<link href="../includes/equip.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../includes/prototype.js"></script>
</head>

<body>
<div id="admin-main">
		<div id="admin-page">
<div id="showAdd">
	<div id="form1-info">
		<p>
		Review the following list and either approve, edit, or deny.
		</p>
	</div>
	<? $i =0; do { $i++; ?>
  	<form id="form<?php echo $i; ?>" name="form<?php echo $i; ?>" action="approve.php" method="post">
	  <div id="form-div">
      <div id="part<?php echo $i; ?>" class="part"><?php echo $i; ?></div>
      <input type="text" id="StudentID<?php echo $i; ?>" name="data[StudentID]" value="<?php echo $row_imports['StudentID']; ?>" />
	  <input type="text" id="Email<?php echo $i; ?>" name="data[Email]" value="<?php echo $row_imports['Email']; ?>" />
	  <input type="text" id="FirstName<?php echo $i; ?>" name="data[FirstName]" value="<?php echo $row_imports['FirstName']; ?>" />
	  <input type="text" id="LastName<?php echo $i; ?>" name="data[LastName]" value="<?php echo $row_imports['LastName']; ?>" />
	  <input type="text" id="Phone<?php echo $i; ?>" name="data[Phone]" value="<?php echo $row_imports['Phone']; ?>" />
	  <input type="text" id="UserID<?php echo $i; ?>" name="data[UserID]" value="<?php echo $row_imports['UID']; ?>" />
	  <input type="hidden" id="ImportID<?php echo $i; ?>" name="data[ImportID]" value="<?php echo $row_imports['ID']; ?>" />
	  <input name="btnDeny<?php echo $i; ?>" type="button" style="margin-left: 35px;" value="Deny" />
	  <input name="btnApprove<?php echo $i; ?>" type="submit" style="margin-left: 35px;" value="Approve" />
	  <hr/ style="border: 0px; height: 3px; background-color: #ffcc00;">
	  </div>
	</form>
	<? } while ($row_imports = mysql_fetch_assoc($import)); ?>
</div>
<p>&nbsp;</p>
</div>
</div>
</body>
</html>
<?php } ?>