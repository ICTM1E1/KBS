<?php
/*
 * @author Jelle Kapitein
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */

$style = "";
$statusText = "";
$naam = "";

if(isset($_GET['status'])) {
    $titel = $_SESSION['msg_titel'];
    $naam = $_SESSION['msg_ontv'];
    $bericht = $_SESSION['msg_bericht'];
    
    $style = "message_error";
    $statusText = "Versturen is mislukt. Controleer de gebruikersnaam.";
} else {
    $titel = "";
    $bericht = "";
}

if(isset($_POST['submit'])) {
    $titel = $_POST['titel'];
    $ontv = $_POST['ontvanger'];
    $bericht = $_POST['bericht'];
    
    if(empty($titel)) {
	$style = "message_error";
	$statusText = "Titel mag niet leeg zijn!";
    } elseif(empty($bericht)) {
	$style = "message_error";
	$statusText = "Bericht mag niet leeg zijn!";
    } else {
	$dbh = connectToDatabase();
	$datum = date("Y-m-d H:i:s", time());
	
	$afz = $_SESSION['clientid'];
	
	$sth = $dbh->prepare("INSERT INTO berichten (titel, afzender, ontvanger, bericht, datum) VALUES (:titel, :afz, '1', :bericht, :datum)");
	$sth->bindParam(":titel", $titel);
	$sth->bindParam(":afz", $afz);
	$sth->bindParam(":bericht", $bericht);
	$sth->bindParam(":datum", $datum);
	$won = $sth->execute();
	
	if($won) {
	    header("Location: /client/berichten/succes");
	} else{
	    $_SESSION['msg_titel'] = $titel;
	    $_SESSION['msg_ontv'] = $ontv;
	    $_SESSION['msg_bericht'] = $bericht;
	    header("Location: /client/berichten/faal");
	}
    }
}
?>

<div class="<?php echo $style; ?>">
    <p><?php echo $statusText; ?></p>
</div>

<table class="simple-table">
    <form action="" method="post">
	<tr>
	    <td>Titel:</td>
	    <td><input type="text" name="titel" size="40" value="<?php echo($titel);?>"/></td>
	</tr>
	<tr>
	    <td>Gebruikersnaam ontvanger:</td>
	    <td><input type="text" disabled="true" name="ontvanger"size="40" value="Beheerder"/></td>
	</tr>
	<tr>
	    <td colspan="2">Bericht:</td>
	</tr>
	<tr>
	    <td colspan="2"><textarea name="bericht" rows="20" cols="70"><?php echo($bericht);?></textarea></td>
	</tr>
	<tr>
	    <td><input type="submit" value="Versturen" name="submit"></td>
	    <td><input type="button" value="Annuleren" onclick="window.location = '/client/berichten'"/></td>
	</tr>
    </form>	
</table>