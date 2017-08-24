<?php
session_start();
include"connect.php";
require_once("adminpages/functions.php");
?>
<html>
	<head>
		<title>Administrator paneel</title>
		<meta charset="UTF-8">
		<meta name="description" content="Administrator paneel">
		<meta name="author" content="Maarten van der Heijden">
		<link rel="stylesheet" type="text/css" href="css/admin.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="js/main.js"></script>
	</head>
	<body>
	<?php
	if(isset($_SESSION['gebruikersnaam'])&&isset($_SESSION['wachtwoord'])){
		$check=0;
		$sql="SELECT * from gebruikers WHERE gebruikersnaam='".$_SESSION['gebruikersnaam']."'";
			foreach ($conn->query($sql) as $row) {
			if($_SESSION['wachtwoord']==$row['wachtwoord']){
			$_SESSION['macht']=$row['macht'];
			$check=1;
			?>
			<div id="container">
				<div id="sidebar">
					<div id="navigatie">
						<table>
							<tr><td onClick="openPage('home')">
								Home
							</td></tr>
							<tr><td onClick="openPage('resultaten')">
								Resultaten
							</td></tr>
							<tr><td onClick="openPage('instellingen')">
								Account instellingen
							</td></tr>
							<?php if($_SESSION['macht']>=2){?>
							<tr><td style="color:gray"><hr>Beheerder:<hr></td></tr>
							<tr><td onClick="openPage('vragenlijst')">
								Vragenlijst aanpassen
							</td></tr>
							<tr><td onClick="openPage('editpage')">
								Pagina instellingen
							</td></tr>
							<?php }
							if($_SESSION['macht']>=3){?>
							<tr><td style="color:gray"><hr>Administrator:<hr></td></tr>
							<tr><td onClick="openPage('gebruikersaccounts')">
								Gebruikersaccounts
							</td></tr>
							<tr><td onClick="openPage('gebruikertoevoegen')">
								Gebruiker toevoegen
							</td></tr>
							<tr><td onClick="openPage('logboek')">
								Logboek
							</td></tr>
							<?php }?>
						</table>
					</div>
				</div>
				<div id="header">
					<div style="padding:20px;">
						<h1 style="color:white;font-size:210%;">Decanaat Adminpaneel</h1>
						<font style="color:White;font-weight:bold">Ingelogd als: <?php echo $_SESSION['naam'];?> - <a style="font-weight:normal;color:White;cursor:pointer" href="index.php">Hoofdpagina</a> - <a style="font-weight:normal;color:White;cursor:pointer" href="admin.php">Pagina herladen</a> - </font> <a style="color:White;cursor:pointer"href="uitloggen.php">Uitloggen</a>
					</div>
				</div>
				<div id="content">
					<div style="padding:10px;">
						<div class="contentblock" id="home">
							<h2>Home</h2>
							<?php include "adminpages/voorpagina.php"; ?>
						</div>
						<div class="contentblock" id="instellingen">
							<?php include "adminpages/profiel.php"; ?>
						</div>
						<div class="contentblock" id="resultaten">
							<?php include "adminpages/resultaten.php"; ?>
						</div>
						<?php if($_SESSION['macht']>=2){?>
						<div class="contentblock" id="vragenlijst">
							<?php include "adminpages/vragenlijst.php";?>
						</div>
						<div class="contentblock" id="editpage">
							<h2>Pas hier de homepage aan</h2>
							<?php include "adminpages/editpage.php";?>
						</div>
						<?php }
							if($_SESSION['macht']>=3){?>
						<div class="contentblock" id="gebruikersaccounts">
							<h2>Gebruikersaccounts</h2>
							<?php include "adminpages/list.php";?>
						</div>
						<div class="contentblock" id="gebruikertoevoegen">
							<?php include "adminpages/gebruikertoevoegen.php";?>
						</div>
						<div class="contentblock" id="logboek">
							<h2>Een logboek dat alle activiteiten bijhoudt</h2>
							<textarea id="textarea_logboek" cols="200" rows="50" readonly><?php include "adminpages/log/activity.log";?></textarea>
							<script>
								$(document).ready(function(){
									var $textarea = $('#textarea_logboek');
									$textarea.scrollTop($textarea[0].scrollHeight);
								})
							</script>
						</div>
							<?php } ?>
						<p style="margin-top:100px;text-align:center;font-size:10px;">Copyright © <script type="text/javascript"> var d = new Date(); document.write(d.getFullYear()) </script> Maarten van der Heijden</p>
						</div>
				</div>
			</div>
		<?php
			} else {
				?><script>window.alert("Gelieve opnieuw in te loggen"); </script><?php
				session_destroy();
				?><script>window.name="";window.location.replace("/admin.php"); </script><?php
			}
		} if($check==0){
			?><script>window.alert("Deze gebruikersnaam en wachtwoord zijn onbekend!"); </script><?php
			session_destroy();
			?><script>window.name="";window.location.replace("/admin.php"); </script><?php
		}
	} else {
	?>
		<div id="login_container">
			<div id="login_header">
				Administrator login
			</div>
			<div id="login_content" style="overflow:hidden">
				<p class="plain_text">Voor deze pagina zijn inloggegevens vereist</p>
				<form action="login.php" method="post">
				<table>
					<tr>
						<td>
							<font class="plain_text">Gebruikersnaam:</font>
						</td>
						<td>
							<input type="text" name="gebruikersnaam" placeholder="Gebruikersnaam" required>
						</td>
					</tr>
					<tr>
						<td>
							<font class="plain_text">Wachtwoord:</font>
						</td>
						<td>
							<input type="password" name="wachtwoord" placeholder="***********" required>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" value="Inloggen" name="button_login">
						</td>
					</tr>
				</table>
				</form>
			</div>
		</div>
		<?php
			}
		?>
	</body>
</html>