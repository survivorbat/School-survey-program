<table>
	<thead>
		<tr>
			<th style="background-color:rgba(0,0,255,0.1)">Gebruikersnaam</th>
			<th style="background-color:rgba(0,0,255,0.3)">Naam</th>
			<th style="background-color:rgba(0,0,255,0.1)">Machtigingen</th>
			<th style="background-color:rgba(0,0,255,0.3)">Titel</th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php
	include "connect.php";
	$sql="SELECT * from gebruikers ORDER BY macht DESC,naam";
	foreach ($conn->query($sql) as $row) {
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
		<tr>
			<td style="background-color:rgba(0,0,255,0.1)"><?php echo $row['gebruikersnaam'];?></td>
			<td style="background-color:rgba(0,0,255,0.3)"><?php echo $row['naam'];?></td>
			<td style="background-color:rgba(0,0,255,0.1)"><?php echo $machtiging;?></td>
			<td style="background-color:rgba(0,0,255,0.3)"><?php echo $row['titel'];?></td>
			<?php if($row['id']!=1){?>
				<td><form action="adminpages/edituser.php" method="post"><input type="hidden" name="userid" value="<?php echo $row['id']; ?>"><input type="submit" name="deluser" value="Verwijderen"></form></td>
				<td><form action="adminpages/edituser.php" method="post"><input type="hidden" name="userid" value="<?php echo $row['id']; ?>"><input type="submit" name="edituser" value="Aanpassen"></form></td>
			<?php } else {?>
			<td><input type="submit" value="Verwijderen" onclick="return confirm('Weet u zeker dat u deze gebruiker wil verwijderen?')" disabled></td>
			<td><input type="submit" value="Aanpassen"  onclick="return confirm('Weet u zeker dat u deze gebruiker wil aanpassen?')"disabled></td>
			<?php } ?>
		</tr>
		<?php
	}
?>
	</tbody>
</table>
