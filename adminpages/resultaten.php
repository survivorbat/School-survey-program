		<h2>Resultaten</h2>
		<?php
			require_once("connect.php");
			echo "Selecteer hier een lijst om de resultaten te verfijnen:"; //De kop van het document, te lui om het in html te zetten
			echo '<ul style="list-style-type:none">'; // start de lijst met lijstnamen
			if(isset($_GET['lijst'])){  //vaststellen of totaal vetgedrukt moet of niet
				$datum = date('c');
				$logfile = fopen("adminpages/log/activity.log",'a+');
				$content = $datum." > ".$_SESSION['gebruikersnaam'].": "."Is looking at list: ".$_GET['lijst']."\r\n";
				fwrite($logfile,$content);
				fclose($logfile);
				echo '<a style="font-weight:normal;text-decoration:none;color:#610B0B" href="admin.php">> Alle vragenlijsten</a>';
			} else {
				echo '<a style="font-weight:bold;text-decoration:none;color:#610B0B" href="admin.php">> Alle vragenlijsten</a>';
			}
			$distinctlist=array(); //Array om aan te geven welke lijstnamen al geweest zijn
			$res = simplexml_load_file("results.xml"); //xml laden
			foreach($res->children() as $antwoorden ){ //allemaal openenen
				$lijstnaam = (string) $antwoorden['lijst']; //naar string omzetten
				if (!in_array($lijstnaam,$distinctlist,true)){ //kijken of hij al i nde lijst staat of niet
					if(isset($_GET['lijst'])){
						if($_GET['lijst']==$lijstnaam){
							echo "<li><a style='font-weight:bold;text-decoration:none;color:#610B0B' href='admin.php?lijst=".$antwoorden['lijst']."'><b>> ".$antwoorden['lijst'].'</b></a></li>';
						} else {
							echo "<li><a style='text-decoration:none;color:#610B0B' href='admin.php?lijst=".$antwoorden['lijst']."'>> ".$antwoorden['lijst'].'</a></li>';
						}
					} else {
						echo "<li><a style='text-decoration:none;color:#610B0B' href='admin.php?lijst=".$antwoorden['lijst']."'>> ".$antwoorden['lijst'].'</a></li>';

					}
					array_push($distinctlist,$lijstnaam); //pushen zodat de lijst groter wordt
				}
			}
			?>
		</ul>
		<br>
		<?php
			if(isset($_GET['lijst'])) {
				$totaalantwoord=0;
				foreach($res->children() as $antwoorden ){
					$lijstnaam = (string) $antwoorden['lijst'];
					if($lijstnaam==$_GET['lijst']){
						$totaalantwoord=$totaalantwoord+1;
					}
				}
			} else {
				$totaalantwoord = $res->antwoord->count();
			}
		?>
		<table>
			<thead>
				<tr><th onclick="$('#totaleresultaten').toggle();reloadContentlength();" style="text-decoration:underline;cursor:pointer;" colspan="2"><< Totale resultaten >></td></tr>
			</thead>
			<tbody id="totaleresultaten">
				<tr>
					<td>Totaal aantal reacties:</td>
					<td><?php echo $totaalantwoord; ?></td>
				</tr>
				<tr>
					<td><br></td>
				</tr>
				<tr class="zeer">
					<td>Totaal aantal 'eens' antwoorden:</td>
					<td id="aantaleens"></td>
				</tr>
				<tr class="goed">
					<td>Totaal aantal 'beetje eens' antwoorden:</td>
					<td id="aantalbeetjeeens"></td>
				</tr>
				<tr class="voldoende">
					<td>Totaal aantal 'neutraal' antwoorden:</td>
					<td id="aantalneutraal"></td>
				</tr>
				<tr class="matig">
					<td>Totaal aantal 'beetje oneens' antwoorden:</td>
					<td id="aantalbeetjeoneens"></td>
				</tr>
				<tr class="slecht">
					<td>Totaal aantal 'oneens' antwoorden:</td>
					<td id="aantaloneens"></td>
				</tr>
				<tr>
					<td><br></td>
				</tr>
				<tr class="zeer">
					<td>Percentage positieve reacties:</td>
					<td id="percpos">%</td>
				</tr>
				<tr class="voldoende">
					<td>Percentage neutrale reacties:</td>
					<td id="percneut">%</td>
				</tr>
				<tr class="slecht">
					<td>Percentage negatieve reacties:</td>
					<td id="percneg">%</td>
				</tr>
				<tr>
					<td><br></td>
				</tr>
				<tr>
					<td>Totaal aantal opmerkingen:</td>
					<td id="aantalopmerkingen"></td>
				</tr>
				<tr>
					<td><br></td>
				</tr>
			</tbody>
		</table>
		<table>
			<thead>
				<tr><th onclick="$('#vraagresultaten').toggle();reloadContentlength()"colspan="6" style="text-decoration:underline;cursor:pointer;"><< Resultaten per vraag >></th></tr>
			</thead>
			<tbody id="vraagresultaten">
				<tr><td><br></td></tr>
				<tr><th style="background-color:rgba(0,0,255,0.1)">Vraag</th><th class="zeer">Eens</th><th class="goed">Beetje eens</th><th class="voldoende">Neutraal</th><th class="matig">Beetje oneens</th><th class="slecht">Oneens</th></tr>
				<?php
				$sql="SELECT * from vragen WHERE actief=1 ORDER BY id";
				$i=0;
				foreach ($conn->query($sql) as $rij) {
					echo '<tr><td style="font-size:85%;background-color:rgba(0,0,255,0.15)">'.$rij['vraag'].'</td>';
					$res = simplexml_load_file("results.xml");
					$eens=0;$beetjeeens=0;$neutraal=0;$beetjeoneens=0;$oneens=0;$opmerkingen=0;
						foreach ($res->antwoord as $antwoord) {
							if(isset($_GET['lijst'])){
							if(isset($antwoord->vraag[$i])){
							foreach($antwoord->vraag[$i]->attributes() as $a => $b){
									if($antwoord['lijst']==$_GET['lijst']){
									if($b==$rij['id']) {
									switch ($antwoord->vraag[$i]->score){
									case 1:
										$oneens=$oneens+1;
										break;
									case 2:
										$mening="<font class='matig'>Beetje oneens</font>";
										$beetjeoneens=$beetjeoneens+1;
										break;
									case 3:
										$mening="<font class='voldoende'>Neutraal</font>";
										$neutraal=$neutraal+1;
										break;
									case 4:
										$mening="<font class='goed'>Beetje eens</font>";
										$beetjeeens=$beetjeeens+1;
										break;
									case 5:
										$mening="<font class='zeer'>Eens</font>";
										$eens=$eens+1;
										break;
								}
							}
						}
						}
						}
					} else {
						if(isset($antwoord->vraag[$i])){
						foreach($antwoord->vraag[$i]->attributes() as $a => $b){
								if($b==$rij['id']) {
								switch ($antwoord->vraag[$i]->score){
								case 1:
									$oneens=$oneens+1;
									break;
								case 2:
									$mening="<font class='matig'>Beetje oneens</font>";
									$beetjeoneens=$beetjeoneens+1;
									break;
								case 3:
									$mening="<font class='voldoende'>Neutraal</font>";
									$neutraal=$neutraal+1;
									break;
								case 4:
									$mening="<font class='goed'>Beetje eens</font>";
									$beetjeeens=$beetjeeens+1;
									break;
								case 5:
									$mening="<font class='zeer'>Eens</font>";
									$eens=$eens+1;
									break;
							}
						}
					}
					}
					}

						}
						echo '<td class="zeer">'.$eens.'</td><td class="goed">'.$beetjeeens.'</td><td class="voldoende">'.$neutraal.'</td><td class="matig">'.$beetjeoneens.'</td><td class="slecht">'.$oneens.'</td></tr>';
						$i=$i+1;
				}

					?>
			</tbody>
		</table>
		<table>
			<thead>
				<tr><th onclick="$('#individueleresultaten').toggle();reloadContentlength()"colspan="2" style="text-decoration:underline;cursor:pointer;"><< Individuele resultaten >></th></tr>
			</thead>
			<tbody id="individueleresultaten">
		<?php
			$eens=0;$beetjeeens=0;$neutraal=0;$beetjeoneens=0;$oneens=0;$opmerkingen=0;$totaalantwoorden=0;
			$res = simplexml_load_file("results.xml");
			if(isset($_GET['lijst'])){ /* Start van blok*/
			foreach ($res->antwoord as $antwoord) {
				$lijstnaam = (string) $antwoord['lijst'];
				$id = (string) $antwoord['id'];
				if($lijstnaam==$_GET['lijst']){
					echo '<tr><td colspan="2"><hr></td></tr>';
					echo '<tr><td colspan="2">Uit lijst: '.$lijstnaam.'</td></tr>';
					echo '<tr><td><b>Vraag:</b></td><td><b>Anwoord:</b></td></tr>';
					$totaal = $antwoord->count();
					for($t=0;$t<$totaal;$t=$t+1){
						if(isset($antwoord->vraag[$t])){
							foreach($antwoord->vraag[$t]->attributes() as $a => $b){
								$score = $antwoord->vraag[$t]->score;
								$db = $conn->prepare("SELECT vraag from vragen WHERE id='$b'");
								$db->execute();
								$vraagnaam = $db->fetch();
								if($vraagnaam=="") {$vraagnaam[0]="<font style='color:gray'>Deze vraag bestaat niet meer! :(</font>";}
								echo "<tr class='kleurrij'><td>" .  $vraagnaam[0] . '</td>';
								switch ($score){
									case 1:
										$mening="<font class='slecht'>Oneens</font>";
										$oneens=$oneens+1;
										$totaalantwoorden=$totaalantwoorden+1;
										break;
									case 2:
										$mening="<font class='matig'>Beetje oneens</font>";
										$beetjeoneens=$beetjeoneens+1;
										$totaalantwoorden=$totaalantwoorden+1;
										break;
									case 3:
										$mening="<font class='voldoende'>Neutraal</font>";
										$neutraal=$neutraal+1;
										$totaalantwoorden=$totaalantwoorden+1;
										break;
									case 4:
										$mening="<font class='goed'>Beetje eens</font>";
										$beetjeeens=$beetjeeens+1;
										$totaalantwoorden=$totaalantwoorden+1;
										break;
									case 5:
										$mening="<font class='zeer'>Eens</font>";
										$eens=$eens+1;
										$totaalantwoorden=$totaalantwoorden+1;
										break;
									case "error":
										$mening="<font color='red'>Error!</font>";
										$neutraal=$neutraal+1;
										$totaalantwoorden=$totaalantwoorden+1;
										break;
								}
								echo "<td>" . $mening . '</td></tr>';
							}
						}
					}
				echo "<tr class='opmerkingrij'><td>Opmerkingen:</td><td>";
				$opmerking = $antwoord->Opmerkingen;
				$opmerking = htmlspecialchars($opmerking);
				$opmerkingen=$opmerkingen+1;
				if($opmerking=="") {$opmerking='<font style="color:gray">Geen</font>'; $opmerkingen=$opmerkingen-1;}
				echo $opmerking;
				echo '</td></tr><tr><td></td></tr>';
				if($_SESSION['macht']>=2){
				echo '<tr><td colspan="2"><form method="post" action="admin.php"><input type="hidden" value="'.$id.'" name="resid"><input type="submit" value="Verwijder dit resultaat" name="verwijderindividueelresultaat"></form></td></tr>';
				}
				echo '<tr><td><br></td></tr>';
				}
			}
			} else { /* Start van blok*/
			foreach ($res->antwoord as $antwoord) {
				$lijstnaam = (string) $antwoord['lijst'];
				$id = (string) $antwoord['id'];
				echo '<tr><td colspan="2"><hr></td></tr>';
				echo '<tr><td colspan="2">Uit lijst: '.$lijstnaam.'</td></tr>';
				echo '<tr><td><b>Vraag:</b></td><td><b>Anwoord:</b></td></tr>';
				$totaal = $antwoord->count();
				for($t=0;$t<$totaal;$t=$t+1){
					if(isset($antwoord->vraag[$t])){
						foreach($antwoord->vraag[$t]->attributes() as $a => $b){
							$score = $antwoord->vraag[$t]->score;
							$db = $conn->prepare("SELECT vraag from vragen WHERE id='$b'");
							$db->execute();
							$vraagnaam = $db->fetch();
							if($vraagnaam=="") {$vraagnaam[0]="<font style='color:gray'>Deze vraag bestaat niet meer! :(</font>";}
							echo "<tr class='kleurrij'><td>" .  $vraagnaam[0] . '</td>';
							switch ($score){
								case 1:
									$mening="<font class='slecht'>Oneens</font>";
									$oneens=$oneens+1;
									$totaalantwoorden=$totaalantwoorden+1;
									break;
								case 2:
									$mening="<font class='matig'>Beetje oneens</font>";
									$beetjeoneens=$beetjeoneens+1;
									$totaalantwoorden=$totaalantwoorden+1;
									break;
								case 3:
									$mening="<font class='voldoende'>Neutraal</font>";
									$neutraal=$neutraal+1;
									$totaalantwoorden=$totaalantwoorden+1;
									break;
								case 4:
									$mening="<font class='goed'>Beetje eens</font>";
									$beetjeeens=$beetjeeens+1;
									$totaalantwoorden=$totaalantwoorden+1;
									break;
								case 5:
									$mening="<font class='zeer'>Eens</font>";
									$eens=$eens+1;
									$totaalantwoorden=$totaalantwoorden+1;
									break;
							}
							echo "<td>" . $mening . '</td></tr>';
						}
					}
				}
				echo "<tr class='opmerkingrij'><td>Opmerkingen:</td><td>";
				$opmerking = $antwoord->Opmerkingen;
				$opmerking = htmlspecialchars($opmerking);
				$opmerkingen=$opmerkingen+1;
				if($opmerking=="") {$opmerking='<font style="color:gray">Geen</font>'; $opmerkingen=$opmerkingen-1;}
				echo $opmerking;
				echo '</td></tr><tr><td></td></tr>';
				if($_SESSION['macht']>=2){
				echo '<tr><td colspan="2"><form method="post" action="admin.php"><input type="hidden" value="'.$id.'" name="resid"><input type="submit" value="Verwijder dit resultaat" name="verwijderindividueelresultaat"></form></td></tr>';
				}
				echo '<tr><td><br></td></tr>';
			}
			}
			?>
			<script>
				$("#aantaleens").text("<?php echo $eens; ?>");
				$("#aantalbeetjeeens").text("<?php echo $beetjeeens; ?>");
				$("#aantalneutraal").text("<?php echo $neutraal; ?>");
				$("#aantalbeetjeoneens").text("<?php echo $beetjeoneens; ?>");
				$("#aantaloneens").text("<?php echo $oneens; ?>");
				$("#aantalopmerkingen").text("<?php echo $opmerkingen; ?>");
				$("#percpos").prepend("<?php echo round(($eens+$beetjeeens)/$totaalantwoorden*100);?>");
				$("#percneut").prepend("<?php echo round($neutraal/$totaalantwoorden*100); ?>");
				$("#percneg").prepend("<?php echo round(($oneens+$beetjeoneens)/$totaalantwoorden*100)?>");
			</script>
			</tbody>
		</table>
		<?php
			if($_SESSION['macht']>=2){
			if(isset($_GET['lijst'])){
			?>
			<form style="margin-top:100px" action="/admin.php" method="post">
				<center>
					<input type="hidden" value="<?php echo $_GET['lijst'];?>" name="lijst">
					<input type="submit" onclick="return confirm('Weet u zeker dat u deze lijst wil verwijderen?')" value="De resultaten uit deze lijst verwijderen" name="removeListResults">
				</center>
			</form>
			<?php } ?>
			<hr>
			<form style="margin-top:50px" action="/admin.php" method="post">
				<center>
					<input type="submit" onclick="return confirm('Weet u zeker dat u alle resultaten wil verwijderen?')" value="Alle resultaten verwijderen" name="removeAllResults">
				</center>
			</form>
			<?php
				if(isset($_POST['removeAllResults'])){
				$datum = date('c');
				$logfile = fopen("adminpages/activity.log",'a+');
				$content = $datum." > ".$_SESSION['gebruikersnaam'].": "."Just removed ALL the results!"."\r\n";
				fwrite($logfile,$content);
				fclose($logfile);
				$resultfile = fopen("results.xml",'w');
						$content = '<data>
		</data>';
					fwrite($resultfile,$content);
					fclose($resultfile);
					?><script>window.name="resultaten";window.location.replace("/admin.php"); </script><?php
				}
				if(isset($_POST['removeListResults'])){
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				$doc = new DOMDocument();
				$doc->Load('results.xml');
				$to_remove = array();
				$datum = date('c');
				$logfile = fopen("adminpages/activity.log",'a+');
				$content = $datum." > ".$_SESSION['gebruikersnaam'].": "."Just removed a list of results with name= ".$_POST['lijst']."\r\n";
				fwrite($logfile,$content);
				fclose($logfile);

				foreach ($doc->getElementsByTagName('antwoord') as $antwoord)
				{
					if(($antwoord->getAttribute('lijst')) == $_POST['lijst']){
						$to_remove[] = $antwoord;
					}
				}
		    foreach ($to_remove as $node)
		    {
		       $node->parentNode->removeChild($node);
		    }
			$doc->Save('results.xml');
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				?><script>window.name="resultaten";window.location.replace("/admin.php");</script><?php
				}
				if(isset($_POST['verwijderindividueelresultaat'])){
					$datum = date('c');
					$logfile = fopen("adminpages/activity.log",'a+');
					$content = $datum." > ".$_SESSION['gebruikersnaam'].": "."Just removed a result!"."\r\n";
					fwrite($logfile,$content);
					fclose($logfile);
					addLog($_SESSION['gebruikersnaam'],"Has just deleted a result!");
					$doc = new DOMDocument();
					$doc->Load('results.xml');
					$to_remove = array();

					foreach ($doc->getElementsByTagName('antwoord') as $antwoord)
				{
					if(($antwoord->getAttribute('id')) == $_POST['resid']){
						$to_remove[] = $antwoord;
					}
				}
				foreach ($to_remove as $node)
				{
				$node->parentNode->removeChild($node);
				}
				$doc->Save('results.xml');
				?><script>window.name="resultaten";window.location.replace("/admin.php");</script><?php
				}

			}
		?>
