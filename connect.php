<?php
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
?>