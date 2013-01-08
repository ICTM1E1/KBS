<?php
/*
 * @author Jelle Kapitein
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $dbh = connectToDatabase();
    
    $sth = $dbh->prepare("SELECT * FROM berichten WHERE id=:id");
    $sth->bindParam(":id", $id);
    $sth->execute();
    
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    
    if($res[0]['gelezen'] == 0) {
	$sth = $dbh->prepare("UPDATE berichten SET gelezen=1 WHERE id=:id");
	$sth->bindParam(":id", $res[0]['id']);
	$sth->execute();
    }
    
    if($res[0]['afzender'] == 1) {
	$naam = "Beheerder";
    } else{
	$sth = $dbh->prepare("SELECT naam FROM user_data WHERE id=:afz");
	$sth->bindParam(":afz", $res[0]['afzender']);
	$sth->execute();
	$res1 = $sth->fetchAll(PDO::FETCH_ASSOC);
	$naam = $res1[0]['naam'];
    }
} else{
    header("Location: /admin/berichten");
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
	<td><?php echo(date("d/m/Y H:i:s", strtotime($res[0]['datum']))); ?></td>
    </tr>
    <tr>
	<td><b>Inhoud:</b></td>
    </tr>
    <tr>
       <td><?php echo($res[0]['bericht']); ?>	</td>
    </tr>
</table>


