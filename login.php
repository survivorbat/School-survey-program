<?php
	session_start();
	function secForm($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	if(isset($_POST['wachtwoord'])&&isset($_POST['gebruikersnaam'])){
		$tot=0;
		include "connect.php";
		$gebruikersnaam=secForm($_POST['gebruikersnaam']);
		$wachtwoord=secForm($_POST['wachtwoord']);
		$sql="SELECT * from gebruikers WHERE gebruikersnaam='$gebruikersnaam'";
		foreach ($conn->query($sql) as $row) {
			$tot=1;
			$dbpassword = $row['wachtwoord'];
			if(password_verify($wachtwoord,$dbpassword)){
				if($row['id']!=1){
					$datum = date('c');
					$logfile = fopen("adminpages/log/activity.log",'a+');
					$content = $datum." > ".$gebruikersnaam.": "."Has just logged in"."\r\n";
					fwrite($logfile,$content);
					fclose($logfile);
				}
				$_SESSION['gebruikersnaam']=$gebruikersnaam;
				$_SESSION['wachtwoord']=$dbpassword;
				$_SESSION['macht']=$row['macht'];
				$_SESSION['titel']=$row['titel'];
				$_SESSION['naam']=$row['naam'];
				$_SESSION['id']=$row['id'];
				?><script>window.location.replace("admin.php");</script><?php
			} else {
				?><script>window.alert("Onbekende gebruikersnaam en/of wachtwoord!"); window.location.replace("admin.php");</script><?php
			}
		}
	} else {
		?><script>window.alert("Er ging iets mis!"); window.location.replace("admin.php"); </script><?php
	}
	if($tot==0){?><script>window.alert("Onbekende gebruikersnaam en/of wachtwoord!"); window.location.replace("admin.php");</script><?php }
?>