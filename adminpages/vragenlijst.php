<h2>Huidige vragenlijst</h2>
<table>
	<thead>
		<tr>
			<th style="background-color:rgba(0,0,255,0.1)">Vraag</th>
			<th style="background-color:rgba(0,0,255,0.3)">Nummer</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php
	$count=0;
	include "connect.php";
	$sql="SELECT * from vragen WHERE actief=1 ORDER BY nummer";
	foreach ($conn->query($sql) as $row) {
		$count=$count+1;
		?>
		<tr>
			<td style="background-color:rgba(0,0,255,0.1)"><?php echo $row['vraag'];?></td>
			<td style="background-color:rgba(0,0,255,0.3)"><?php echo $row['nummer'];?></td>
			<?php if($_SESSION['macht']>=2){?>
				<td>
					<form action="adminpages/deletequestion.php" method="post">
						<input type="hidden" name="Qid" value="<?php echo $row['id'];?>">
						<input type="submit" onclick="return confirm('Weet u zeker dat u deze vraag wil verwijderen?')" value="Deze vraag verwijderen" name="DeleteQ">
					</form>
				</td>
			<?php } ?>
		</tr>
		<?php
	}
?>
	</tbody>
</table>
<?php
	if($_SESSION['macht']>=2){
?>
	<h2>Een vraag toevoegen:</h2>
	<form action="adminpages/addquestion.php" method="post">
	<table>
		<tr>
			<td colspan="2" style="text-align:center;color:rgb(100,100,100)">Onthoud: De vragen worden met oneens, matig, neutraal, een beetje of eens beantwoord.</td>
		</tr>
		<tr>
			<td style="background-color:rgb(235,235,255)">
				Vraag:
			</td>
			<td style="background-color:rgb(225,225,255)">
				<input type="text" placeholder="Vraag" name="vraag" size="60">
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="hidden" value="<?php echo $count+1; ?>" name="nummer">
				<input type="submit" value="Toevoegen" name="submitknop" size="40">
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