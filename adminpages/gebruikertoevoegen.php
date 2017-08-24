<?php
	if($_SESSION['macht']>=3){
?>
	<h2>Voeg een gebruiker toe:</h2>
	<form action="adminpages/adduser.php" method="post">
	<table>
		<tr>
			<td>
				Gebruikersnaam:
			</td>
			<td>
				<input type="text" placeholder="Gebruikersnaam" name="gebruikersnaam" size="40" required>
			</td>
		</tr>
		<tr>
			<td>
				Volledige naam:
			</td>
			<td>
				<input type="text" placeholder="Naam" name="naam" size="40" required>
			</td>
		</tr>
		<tr>
			<td>
				Wachtwoord:
				<br><font style="color:rgb(100,100,100)"><i>Wachtwoorden worden altijd versleuteld via een hashfunctie</i></font>
			</td>
			<td>
				<input type="text" placeholder="Wachtwoord" name="wachtwoord" size="40" required>
			</td>
		</tr>
		<tr>
			<td>
				Niveau:<br><font style="color:rgb(100,100,100)"><i>3=Ontwikkelaar 2=Administrator 1=Gebruiker</i></font>
			</td>
			<td>
				<input type="text" placeholder="Niveau" name="macht" size="40" required>
			</td>
		</tr>
		<tr>
			<td>
				Titel:
			</td>
			<td>
				<input type="text" placeholder="Titel" name="titel" size="40" required>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" value="Opslaan" name="submitknop" size="40">
			</td>
		</tr>
	</table>
	</form>
<?php	
	} else {
?>
Geen bevoegdheid voor deze pagina.
<?php
	}
?>