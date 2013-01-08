<?php
/*
 * @author Jelle Kapitein
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */

$dbh = connectToDatabase();
$style = "";
$statusText = "";

if(isset($_POST['submit'])) {
    
}

if(isset($_GET['status'])) {
    $style = "message_success";
    $statusText = "Bericht verzonden";
}

if(isset($_GET['name'])) {
    $sth = $dbh->prepare("SELECT id FROM user_data WHERE naam=:naam");
    $sth->bindParam(":naam", $_GET['name']);
    $sth->execute();
    $user = $sth->fetchAll(PDO::FETCH_ASSOC);
    $id = $naam[0]['id'];
    $naam = $_GET['name'];
    
    $sth = $dbh->prepare("SELECT id,titel,afzender,datum,gelezen FROM berichten WHERE afzender=:name ORDER BY gelezen ASC, datum DESC");
    $sth->bindParam(":name", $id);
    $sth->execute();
    
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    print_r($res);    
} elseif(isset($_POST['search'])) {
    // zoek
} else {
    $sth = $dbh->prepare("SELECT id,titel,afzender,datum,gelezen,naam FROM berichten JOIN user_data ON afzender=user_id WHERE ontvanger='1' ORDER BY gelezen ASC, datum DESC");
    $sth->execute();
    
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);   
}
?>

<div class="<?php echo $style; ?>">
    <p><?php echo $statusText; ?></p>
</div>

<form action="" method="post">
    <input type="button" onclick="window.location = '/admin/berichten/nieuw'" value="Nieuw"/>
    <input type="submit" name="option" value="Verwijderen"/>
    <br/>
    <input type="text" name="search" placeholder="Zoeken..."/>
    <br/><br/>
    <?php 
	if(empty($res)) {
	    echo("Geen berichten om weer te geven.");
	    } else { ?>
		<table>
		    <tr>
			<td class="center"><input type="checkbox" id="checkall" value=""/></td>
			<th>Titel</th>
			<th>Afzender</th>
			<th class="center">Datum verzonden</th>
			<th class="center">Gelezen</th>
		    </tr>
		<?php foreach($res as $row) { ?>
		    <tr>
			<td class="center"><input type="checkbox" name="id[]" value="<?php echo($row['id']); ?>"/></td>
			<td><a href="/admin/bericht/<?php echo($row['id']);?>"><?php echo($row['titel']);?></a></td>
			<td><?php echo($row['naam']); ?></td>
			<td class="center"><?php echo(date("d-m-Y H:i:s", strtotime($row['datum']))); ?></td>
			<td class="center"><?php echo($row['gelezen'] == 1 ? "Ja" : "Nee"); ?></td>
		    </tr>
		<?php } ?>
		</table>
	<?php } ?>
</form>
