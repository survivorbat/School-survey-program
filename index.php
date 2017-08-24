<?php
	$config = parse_ini_file("adminpages/settings.ini");
	function secForm($data) {
		$data = preg_replace("/[<>]/"," ",$data);
		$data = strip_tags($data);
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
?>
<html>
	<head>
		<title><?php echo $config['titel']; ?></title>
		<meta charset="UTF-8">
		<meta name="description" content="Decanaat Bergen op Zoom evaluatie formulier">
		<meta name="author" content="Maarten van der Heijden">
		<meta name="keywords" content="Roncalli,Bergen op Zoom,formulier,evaluatie">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="img/favicon.ico" type="image/x-icon" />
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="js/main.js"></script>
	</head>
	<body>
		<div id="container">
			<img src="img/banner.png" width="100%">
			<h1><?php echo $config['titel']; ?> feedback formulier</h1>
			<hr>
			<br>
			<?php if(isset($_POST['submitknop'])){
				$datum = date('c');
				$logfile = fopen("adminpages/log/activity.log",'a+');
				$content = $datum." > Server: A new result was added!\r\n";
				fwrite($logfile,$content);
				fclose($logfile);
				$countini = parse_ini_file("counter.ini");
				$counterfile = fopen("counter.ini",'w');
				$content = $countini['counter']+1;
				$content = "counter=".$content;
				fwrite($counterfile,$content);
				fclose($counterfile);
				$_POST['overig']=secForm($_POST['overig']);

				$commentlength=strlen($_POST['overig']);
				if($commentlength>=700){
					$_POST['overig'] = substr($_POST['overig'], 0, 700);
				}
				$file="results.xml";
				$resultfile = simplexml_load_file($file);
				$antwoord = $resultfile->antwoorden;
				$antwoord = $resultfile->addChild('antwoord');
				for($i=1;$i<=$_POST['total'];$i=$i+1) { //Elke vraag die er is wordt opgeschreven
					$vraagID=secForm($_POST["vraagnummer$i"]);
					$vraagantwoord=$_POST["v$i"];
					$vraagkol = $antwoord->addChild('vraag'); //Het nummer van de vraag, dit kun je later opvragen
					$vraagnum = $vraagkol->addAttribute('vraagnummer',$vraagID);
					$allowedscores = array(1,2,3,4,5);
					if(in_array($vraagantwoord,$allowedscores)){
						$vraagkol->addChild('score', $vraagantwoord); //Per vraag zodat i goed zit
					} else {
						$vraagkol->addChild('score', 3); //Per vraag zodat i goed zit
					}

				}
				$antwoord->addChild('Opmerkingen', $_POST['overig']);
				$antwoord->addAttribute('lijst',$config['titel']);
				$antwoord->addAttribute('id',$countini['counter']);
				$resultfile->asXML($file);
				/*The backup file*/
				$file="backup.xml";
				$resultfile = simplexml_load_file($file);
				$antwoord = $resultfile->antwoorden;
				$antwoord = $resultfile->addChild('antwoord');
				for($i=1;$i<=$_POST['total'];$i=$i+1) { //Elke vraag die er is wordt opgeschreven
					$vraagID=$_POST["vraagnummer$i"];
					$vraagantwoord=$_POST["v$i"];
					$vraagkol = $antwoord->addChild('vraag'); //Het nummer van de vraag, dit kun je later opvragen
					$vraagnum = $vraagkol->addAttribute('vraagnummer',$vraagID);
					$vraagkol->addChild('score', $vraagantwoord); //Per vraag zodat i goed zit

				}
				$antwoord->addChild('Opmerkingen', $_POST['overig']);
				$antwoord->addAttribute('lijst',$config['titel']);
				$antwoord->addAttribute('id',$countini['counter']);
				$resultfile->asXML($file);
			?>
			<h1>Hartelijk dank voor het invullen!</h1>
			<?php
			} else {
			?>
			<font class="top_text"><?php echo $config['welkomtekst']; ?></font>
			<br>
			<br>
			<form action="index.php" method="post">
				<table style="width:100%;">
					<?php include"vragen.php"; ?>
					<tr>
						<td colspan="5">
							<textarea id="comments" name="overig" cols="30" rows="10" onFocus="checkComments()" placeholder="<?php echo $config['textarea']; ?>"></textarea><br><span style="margin-top:5px;"id="commentCounter">0 van het maximaal aantal (700) tekens gebruikt.</span><br><span id="commentnot"></span>
							<script>
								function checkComments() {
									$("#comments").keyup(function(){
										var value=$("#comments").val();
										$("#commentCounter").text(value.length+" van het maximaal aantal (700) tekens gebruikt.")
										if(value.length>700){
											$("#commentnot").html("<font color='red'>Uw opmerking mag niet langer zijn dan 700 tekens!</font>");
											$("#verstuurknop").prop("disabled",true);
										} else {
											$("#commentnot").html("");
											$("#verstuurknop").prop("disabled",false);
										}
									})
								}
							</script>
						</td>
					</tr>
					<tr>
						<td colspan="5"><input id="verstuurknop"type="submit" name="submitknop" value="Versturen"><input type="hidden" name="total" value="<?php echo $total;?>"></td>
					</tr>
				</table>
			</form>
			<?php
			}
			?>
			<p style="marin-top:300px;text-align:center;font-size:10px;">Copyright © <script type="text/javascript"> var d = new Date(); document.write(d.getFullYear()) </script> Maarten van der Heijden</p>
		</div>
	</body>
</html>
