<h2>Dit zijn uw gegevens:</h2>
<table>
	<tbody>
<?php
	include "connect.php";
	$userid = $_SESSION['id'];
	$sql = $conn->prepare("SELECT * from gebruikers WHERE id=:id");
	$sql->bindParam(':id', $userid);
	$sql->execute(); 
	$row = $sql->fetch();
	$gebruikersnaam=$row['gebruikersnaam'];
	$naam=$row['naam'];
	$titel=$row['titel'];
	switch($row['macht']){
		case 1:
			$machtiging="Gebruiker";
			break;
		case 2:
			$machtiging="Beheerder";
			break;
		case 3:
			$machtiging="Administrator";
			break;
		case 4:
			$machtiging="Ontwikkelaar";
			break;
	}
		?>
		<tr><td>Uw gebruikersnaam:</td><td><?php echo $gebruikersnaam;?></td></tr>
		<tr><td>Uw volledige naam:</td><td><?php echo $naam;?></td></tr>
		<tr><td>Machtigingen op de website:</td><td><?php echo $machtiging;?></td>
		<tr><td>Titel:</td><td><?php echo $titel;?></td>
		<tr><td>Wachtwoord:</td><td style="color:gray">Wachtwoorden worden niet weergegeven</td></tr>
		<tr><td colspan="2"><input type="submit" onClick='$("#editStats").show()' value="Deze gegevens aanpassen"></td></tr>
	</tbody>
</table><br><br>
<div style="background-color:rgba(100,100,255,0.2);border-radius:15px;"class="hideThis" id="editStats">
	<table>
		<form action="admin.php" method="post">
			<tr><td colspan="2">Pas uw gegevens aan:</td></tr>
			<tr><td>Uw volledige naam:</td><td><input type="text" value="<?php echo $naam;?>" name="naam" size="30"></td></tr>
			<tr><td>Uw titel:</td><td><input type="text" value="<?php echo $titel;?>" name="titel" size="30"></td></tr>
			<tr><td colspan="2"><input type="submit" value="Aanpassingen doorvoeren" name="editUserSettings"></td></tr>
		</form>
		<form action="admin.php" method="post">
			<tr><td colspan="2"><hr></td></tr>
			<tr><td colspan="2">Uw wachtwoord aanpassen:</td></tr>
			<tr><td>Een nieuw wachtwoord instellen:</td><td><input type="password" id="newpassword" name="wachtwoord" size="30"></td></tr>
			<tr><td>Herhaal het nieuwe wachtwoord:</td><td><input type="password" onClick="passwordCheck()" id="secondnewpasswordcheck" size="30"></td></tr>
			<tr><td></td><td id="passwordcheckdisplay"></td></tr>
			<tr><td colspan="2"><input type="submit" value="Wachtwoord aanpassen" id="editpasswordbutton" name="editUserPassword" disabled></td></tr>
			<tr><td colspan="2"><hr></td></tr>
		</form>
	</table>
</div>

<?php
	if(isset($_POST['editUserSettings'])){
		$sqlupdate = $conn->prepare("UPDATE gebruikers SET naam=:naam,titel=:titel WHERE id=:id");
		$sqlupdate->bindParam(':id', $userid);
		$sqlupdate->bindParam(':naam', $_POST['naam']);
		$sqlupdate->bindParam(':titel', $_POST['titel']);
		$sqlupdate->execute();
		?><script>window.name="instellingen";window.location.replace("/admin.php"); </script><?php
	} elseif(isset($_POST['editUserPassword'])){
		$wachtwoordhashed=password_hash($_POST['wachtwoord'],PASSWORD_DEFAULT);
		$sqlupdate = $conn->prepare("UPDATE gebruikers SET wachtwoord=:wachtwoord WHERE id=:id");
		$sqlupdate->bindParam(':id', $userid);
		$sqlupdate->bindParam(':wachtwoord', $wachtwoordhashed);
		$sqlupdate->execute();
		?><script>window.name="instellingen";window.location.replace("/admin.php"); </script><?php
	}
?>