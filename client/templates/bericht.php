<?php
/*
 * @author Jelle Kapitein
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */

if(isset($_GET['id'])) { // Als de ID is geset, haal het bericht op
    $id = $_GET['id'];
    $cid = $_SESSION['clientid'];
    $dbh = connectToDatabase();
    
    $sth = $dbh->prepare("SELECT * FROM berichten WHERE id=:id AND ontvanger=:cid"); // controle of het bericht wel naar de client is gestuurd
    $sth->bindParam(":id", $id);
    $sth->bindParam(":cid", $cid);
    $sth->execute();
    
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    
    if(empty($res)) { // Als het bericht niet is gevonden, stuur de client terug naar het overzicht
	header("Location: /client/berichten");
    }
    
    if($res[0]['gelezen'] == 0) { // Als het bericht nog niet eerder gelezen was, nu op gelezen zetten.
	$sth = $dbh->prepare("UPDATE berichten SET gelezen=1 WHERE id=:id");
	$sth->bindParam(":id", $res[0]['id']);
	$sth->execute();
    }
    
    if($res[0]['afzender'] == 1) { // Als ID = 1, dan naam = beheerder
	$naam = "Beheerder";
    } else{ // Anders naam ophalen
	$sth = $dbh->prepare("SELECT naam FROM user_data WHERE id=:afz");
	$sth->bindParam(":afz", $res[0]['afzender']);
	$sth->execute();
	$res1 = $sth->fetchAll(PDO::FETCH_ASSOC);
	$naam = $res1[0]['naam'];
    }
} else{
    header("Location: /client/berichten");
}
?>

<table class="simple-table">
    <tr>
	<td><b>Naam afzender:</b></td>
	<td><?php echo($naam); ?></td>
    </tr>
    <tr>
	<td><b>Titel bericht:</b></td>
	<td><?php echo($res[0]['titel']); ?></td>
    </tr>
    <tr>
	<td><b>Datum verstuurd:</b></td>
	<td><?php echo(date("d-m-Y H:i:s", strtotime($res[0]['datum']))); ?></td>
    </tr>
    <tr>
	<td><b>Inhoud:</b></td>
    </tr>
    <tr>
       <td><?php echo($res[0]['bericht']); ?>	</td>
    </tr>
</table>


