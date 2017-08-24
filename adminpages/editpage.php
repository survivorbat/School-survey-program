<?php
	require_once("functions.php");
	$config = parse_ini_file("settings.ini");
	if(isset($_POST['changefrontpage'])){
		addlog("Server","The front page was edited!");
		$settingfile = fopen("settings.ini",'w');
		$tekst = nl2br($_POST['welkomtekst']);
		$tekst = trim(preg_replace('/\s\s+/', ' ', $tekst));
		$content = "titel=".$_POST['titel']."\nwelkomtekst=".$tekst."\ntextarea=".$_POST['textarea'];
		fwrite($settingfile,$content);
		?><script>window.name="editpage";window.location.replace("/admin.php");</script><?php
	} else {
		$tekst=$config['welkomtekst'];
		$breaks = array("<br />","<br>","<br/>");  
		$tekst = str_ireplace($breaks,"\r\n",$tekst); 
?>
<form action="/adminpages/editpage.php" method="post">
<table>
	<thead>
		<tr>
			<th>Plek of eigenschap</th>
			<th>Waarde</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Titel van de gelegenheid<br>(dus wat er bovenaan de pagina staat)</td>
			<td><input type="text" name="titel" size="20" value="<?php echo $config['titel']; ?>"> feedback formulier<br><font color="gray">Let extra goed op of dat er geen spelfouten in de titel staan<br><br></font></td>
		</tr>
		<tr>
			<td>Tekst die boven de vragenlijst staat</td>
			<td><textarea name="welkomtekst" cols="60" rows="20"><?php echo $tekst; ?></textarea></td>
		</tr>
		<tr>
			<td>Doorzichtige tekst in het 'opmerkingen' veld</td>
			<td><textarea name="textarea" cols="30" rows="5"><?php echo $config['textarea']; ?></textarea></td>
		</tr>
		<tr>
			<td  colspan="2"><input type="submit" value="Opslaan" name="changefrontpage"></td>
		</tr>
	</tbody>
</table>
</form>

<?php } ?>