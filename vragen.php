<?php
	include "connect.php";
	$total=0;
	$sql="SELECT * from vragen WHERE actief=true ORDER BY nummer";
	foreach ($conn->query($sql) as $row) {
		$total=$total+1
		?>
		<tr>
			<td class="vraag" colspan="5"><?php echo $row['vraag']; ?></td>
		</tr>
		<tr>
			
			<td class="slecht">Oneens<Br><input type="radio" name="v<?php echo $total; ?>" value="1"><input type="hidden" name="vraagnummer<?php echo $total; ?>" value="<?php echo $row['id'];?>" required></td>
			<td class="matig">Een beetje oneens<Br><input type="radio" name="v<?php echo $total; ?>" value="2" required></td>
			<td class="voldoende">Neutraal<Br><input type="radio" name="v<?php echo $total; ?>" value="3" required></td>
			<td class="goed">Een beetje eens<Br><input type="radio" name="v<?php echo $total; ?>" value="4" required></td>
			<td class="zeer">Eens<Br><input type="radio" name="v<?php echo $total; ?>" value="5" required></td>
		</tr>
		<tr>
			<td colspan="5"><br></td>
		</tr>
		<?php
	}
	if($total==0){
		echo "<b>Er zijn helaas momenteel geen vragen ingesteld.</b>";
	}
?>