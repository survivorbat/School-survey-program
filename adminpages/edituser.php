<?php
	session_start();
	require_once("functions.php");
?>
<meta name="description" content="Administratorpaneel">
<meta charset="UTF-8">
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
	if($_SESSION['macht']>=3){
		if(isset($_POST['deluser'])){
			addlog($_SESSION['gebruikersnaam'],"Removed account with id=".$_POST['userid']);
			$verwijderditid=$_POST['userid'];
			$stmt = $conn->prepare("DELETE FROM gebruikers WHERE id=$verwijderditid");
			$stmt->execute();
		?><script>window.name="gebruikersaccounts";window.location.replace("/admin.php"); </script><?php
		} elseif(isset($_POST['edituser'])){
?>
	<h2>Pas deze gebruiker aan::</h2>
<form action="edituser.php" method="post">
<table>
	<tbody>
<?php
	$userid = $_POST['userid'];
	$sql = $conn->prepare("SELECT * from gebruikers WHERE id=:id");
	$sql->bindParam(':id', $userid);
	$sql->execute(); 
	$row = $sql->fetch();
	$gebruikersnaam=$row['gebruikersnaam'];
	$naam=$row['naam'];
	$titel=$row['titel'];
	$macht=$row['macht'];
	addLog($_SESSION['gebruikersnaam'],"Is editing: ".$gebruikersnaam);
		?>
		<tr><td>Gebruikersnaam:</td><td><input type="text" value="<?php echo $gebruikersnaam;?>" name="gebruikersnaam" size="30"></td></tr>
		<tr><td>Uw volledige naam:</td><td><input type="text" value="<?php echo $naam;?>" name="naam" size="30"></td></tr>
		<tr><td>Titel:</td><td><input type="text" value="<?php echo $titel;?>" name="titel" size="30"></td></tr>
		<tr><td>Machtigingen op de website:</td><td><input type="text" value="<?php echo $macht;?>" name="macht" size="30"></td></tr>
		<tr><td>Nieuw wachtwoord:</td><td style="color:gray"><input type="password" name="wachtwoord" size="30"><input type="hidden" name="id" value="<?php echo $userid; ?>" size="30"></td></tr>
		<tr><td colspan="2"><input type="submit" name="edituserconf" value="Deze gegevens aanpassen"></td></tr>
	</tbody>
</table>
</form>
<?php	
	} elseif(isset($_POST['edituserconf'])){
		if($_POST['wachtwoord']!=""){
			
			$sqlupdate = $conn->prepare("UPDATE gebruikers SET naam=:naam,titel=:titel,gebruikersnaam=:username,macht=:macht,wachtwoord=:wachtwoord WHERE id=:id");
			$wachtwoordhashed=password_hash($_POST['wachtwoord'],PASSWORD_DEFAULT);
			$sqlupdate->bindParam(':wachtwoord', $wachtwoordhashed);
		} else {
			$sqlupdate = $conn->prepare("UPDATE gebruikers SET naam=:naam,titel=:titel,gebruikersnaam=:username,macht=:macht WHERE id=:id");
		}
		addlog($_SESSION['gebruikersnaam'],"Changed user with username=".$_POST['gebruikersnaam']);
		$sqlupdate->bindParam(':id', $_POST['id']);
		$sqlupdate->bindParam(':username', $_POST['gebruikersnaam']);
		$sqlupdate->bindParam(':naam', $_POST['naam']);
		$sqlupdate->bindParam(':titel', $_POST['titel']);
		$sqlupdate->bindParam(':macht', $_POST['macht']);
		$sqlupdate->execute();
		?><script>window.name="gebruikersaccounts";window.location.replace("/admin.php");</script><?php
	}
}
?>