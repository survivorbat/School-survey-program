<?php
	include"connect.php";
	$stmt = $conn->prepare("INSERT INTO gebruikers (gebruikersnaam, wachtwoord) VALUES (:name, :value)");
	$stmt->bindParam(':name', $name);
	$stmt->bindParam(':value', $value);
	
	$value = password_hash("123",PASSWORD_BCRYPT);
	$name = "Maartentest";
	$stmt->execute();
	
	
?>