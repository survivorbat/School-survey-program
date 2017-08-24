<?php
session_start();
if($_SESSION['gebruikersnaam']!="ronc_admin"){
	$datum = date('c');
	$logfile = fopen("adminpages/log/activity.log",'a+');
	$content = $datum." > ".$_SESSION['gebruikersnaam'].": "."Has logged out"."\r\n";
	fwrite($logfile,$content);
	fclose($logfile);
}
session_destroy();
?>
<script>window.alert("Succesvol uitgelogd!"); window.location.replace("admin.php"); </script>