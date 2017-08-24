<?php
	session_start();
	require_once("functions.php");
	$username="U2873502";
	$password="Zilverisblijxx1";
	try {
	$conn = new PDO("mysql:host=rdbms.strato.de;dbname=DB2873502", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e)
		{
		echo "Connection failed: " . $e->getMessage();
		}
	if(isset($_POST['submitknop'])){
		addlog($_SESSION['gebruikersnaam'],"Just added user ".$_POST['gebruikersnaam']);
		$wachtwoordhashed=password_hash($_POST['wachtwoord'],PASSWORD_DEFAULT);
		$stmt = $conn->prepare("INSERT INTO gebruikers (gebruikersnaam, naam, wachtwoord, macht, titel) VALUES (:gebruikersnaam, :naam, :wachtwoord, :macht, :titel)");
		$stmt->bindParam(':gebruikersnaam', $_POST['gebruikersnaam']);
		$stmt->bindParam(':naam', $_POST['naam']);
		$stmt->bindParam(':wachtwoord', $wachtwoordhashed);
		$stmt->bindParam(':macht', $_POST['macht']);
		$stmt->bindParam(':titel', $_POST['titel']);
		$stmt->execute();
		?><script>window.name="gebruikersaccounts";window.location.replace("/admin.php"); </script><?php
	} else {
		?><script>window.alert("Er ging iets mis!"); window.location.replace("/admin.php"); </script><?php
	}
?>