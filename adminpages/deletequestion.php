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
	if(isset($_POST['DeleteQ'])){
		addlog($_SESSION['gebruikersnaam'],"Just deleted a question!");
		$verwijderditid=$_POST['Qid'];
		$stmt = $conn->prepare("UPDATE vragen SET actief=0 WHERE id=$verwijderditid");
		$stmt->execute();
		?><script>window.name="vragenlijst";window.location.replace("/admin.php"); </script><?php
	} else {
		?><script>window.alert("Er ging iets mis!"); window.location.replace("/admin.php"); </script><?php
	}
?>