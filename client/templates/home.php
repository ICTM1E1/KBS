<?php
/*
 * @author Jelle Kapitein
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */

$dbh = connectToDatabase();

$sth = $dbh->prepare("SELECT * FROM user_data WHERE user_id=:id");
$sth->bindParam(":id", $_SESSION['clientid']);
$sth->execute();
$res = $sth->fetchAll(PDO::FETCH_ASSOC);
?>
<br/>
<p>Indien deze informatie niet klopt, gelieve zo spoedig mogelijk contact opnemen met de beheerder middels het tabblad "Berichten"</p>
<br/><br/>
<table class="simple-table">
    <tr>
	<td><b>Naam:</b></td>
	<td><?php echo($res[0]['naam']); ?></td>
    </tr>
    <tr>
	<td><b>E-mail adres:</b></td>
	<td><?php echo($res[0]['email']); ?></td>
    </tr>
    <tr>
	<td><b>Adres:</b></td>
	<td><?php echo($res[0]['adres']); ?></td>
    </tr>
    <tr>
	<td><b>Postcode:</b></td>
	<td><?php echo($res[0]['postcode']); ?></td>
    </tr>
    <tr>
	<td><b>Woonplaats:</b></td>
	<td><?php echo($res[0]['woonplaats']); ?></td>
    </tr>
    <tr>
	<td><b>Telefoon nummer:</b></td>
	<td><?php echo($res[0]['telefoon']); ?></td>
    </tr>
    <tr>
	<td><b>Mobiele nummer:</b></td>
	<td><?php echo($res[0]['mobiel']); ?></td>
    </tr>
</table>