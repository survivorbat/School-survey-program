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
		echo "Er ging iets mis met het verwerken van de aanvraag! Bestaat deze gebruikersnaam niet al?";
		}
	if(isset($_POST['submitknop'])){
		addlog($_SESSION['gebruikersnaam'],"Just added a new question: ".$_POST['vraag']);
		$stmt = $conn->prepare("INSERT INTO vragen (vraag,nummer) VALUES (:vraag, :nummer)");
		$stmt->bindParam(':vraag', $_POST['vraag']);
		$stmt->bindParam(':nummer', $_POST['nummer']);
		$stmt->execute();
		?><script>window.name="vragenlijst";window.location.replace("/admin.php"); </script><?php
	} else {
		?><script>window.alert("Er ging iets mis!"); window.location.replace("/admin.php"); </script><?php
	}
?>