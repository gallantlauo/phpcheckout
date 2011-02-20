<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_equip = "localhost";
$database_equip = "db_phpcheckout";
$username_equip = "db_user";
$password_equip = "password";
$equip = mysql_pconnect($hostname_equip, $username_equip, $password_equip) or die(mysql_error());
# auth methods, either database or ldap for checkout users only
# all students must be added to database manually or imported to mysql
$ldap_enabled = false; 
$ldap_cal = false;
# options to enable kit-specific due date change to true
$checkHours = false; 
# default hours allowed per kit
$defaultHours = 48; 
# number of nights allowed out
$overnights = 2; 
# only shows items due today on index page, false shows all
$dueToday = true; 
# if open weekends, flag this
$weekends = false; 
# three-digit textual day, usually Sat
$dayClosed1 = "Sun"; 
$dayClosed2 = "Mon"; 
# due back BEFORE time
$dueHours = "17:00:00"; 
# 24 hour time, with minutes and seconds 00:00:00
# set your open and close times here. System will only work with Hour arguments, 
# input hour in 24 hour time in format HH
$monOpen = 00;
$monClose = 00;
$tueOpen = 14;
$tueClose = 17;
$wedOpen = 14;
$wedClose = 17;
$thuOpen = 14;
$thuClose = 17;
$friOpen = 14;
$friClose = 17;
$satOpen = 14;
$satClose = 17;
$sunOpen = 00;
$sunClose = 00;

// USE FINES OR STRIKES
$fines = true;
// if $fines are false, strikes are enabled
$maxStrike = 3;
$gracePeriod = 1800; // grace period in seconds; for strikes as fines ignores the fine unless the full frequency time has passed.
$fineAmount = 1.00; // amount of fine or strike
$fineFreq = 86400; // amount of time per fine increment, in seconds, default is one day.
$maxFine = 3.00; // max amount of fine or strike per kit

// UID (ALPHANUMERIC) VS STUDENT ID (NUMERIC)
/* 
Some universities give students an identification card with a barcode and Student ID 
number. That is what this implementation assumes. The User ID is usually different and
used for logging in to services such as blackboard, a cms portal, or email within 
the university. The user_id is used for the reservations portion of phpcheckout only
and it may be used in conjunction with an ldap server. If you use ldap, both uids and
sids will be used as a default. If your school does not use numeric-only student ids,
then enable $sid_as_uid to be true.
*/

$sid_as_uid = false; // if true, student_id becomes alphanumeric and mirrors the uid.
$minLength = 12; // minimum length for student id. if above is true, set to something like 5.

// Directory info
$site_root = "http://www.mysite.com"; //domain name where your installation of checkout resides, no trailing slash
$root = "/phpcheckout/equipment"; // using absolute paths. from the root to the program folder, with preceding slash and without trailing slash
$cal_root = "/phpcheckout/cal"; 

// admin name for clearing and paying strikes, also for adding users; if ldap enabled, must be a valid ldap user
$adminName['name'][] = "admin";
$adminName['name'][] = "admin2";
$adminName['name'][] = "admin3";
// admin email for error reporting
$admin_email1 = "wturnerharris@gmail.com";
$admin_email2 = "";
$admin_email3 = "";
// City College Note Specific
$alert['classes'] = "Do NOT add any students to any of the Photo Classes without permission from manager.";
$alert['else1'] = "";

// =-=-=-=-=-= DO NOT EDIT BELOW THIS LINE =-=-=-=-=-=-=

?>