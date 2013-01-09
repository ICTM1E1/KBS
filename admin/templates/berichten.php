<?php
/*
 * @author Jelle Kapitein
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */

$dbh = connectToDatabase();
$style = "";
$statusText = "";

if(isset($_POST['submit']) && isset($_POST['id'])) {
    $id = $_POST ['id']; // Haal de ID array op
    $id = implode(',', $_POST ['id']); // Zet de array om in een string, uit elkaar gehouden door ,
    $id = mysql_real_escape_string($id); // Maak de string veilig voor de database
    $sth = $dbh->prepare("DELETE FROM berichten WHERE id IN(" . $id . ")"); // Verwijder het bericht
    $result = $sth->execute();
    if ($result == true) {
	$style = 'message_success';
	$statusText = "Bericht(en) succesvol verwijderd.";
    } else {
	$style = 'message_error';
	$statusText = "Er is een fout opgetreden tijdens het verwijderen van het bericht.";
    }
}

if(isset($_GET['status'])) {
    $style = "message_success";
    $statusText = "Bericht verzonden";
}

if(isset($_GET['naam'])) { // Als de naam geset is, haal alleen berichten op van die persoon.
    $naam = $_GET['naam'];
    
    $sth = $dbh->prepare("SELECT id,titel,afzender,datum,gelezen,naam FROM berichten JOIN user_data ON afzender=user_id WHERE afzender=:name ORDER BY gelezen ASC, datum DESC");
    $sth->bindParam(":name", $naam);
    $sth->execute();
    
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
} elseif(isset($_POST['search'])) { // Als er op een titel/naam gezocht is, laat alleen die zien.
    $search = "%".$_POST['search']."%";
    
    $sth = $dbh->prepare("SELECT id,titel,afzender,gelezen,datum,naam FROM berichten JOIN user_data ON afzender=user_id WHERE ontvanger='1' AND (titel LIKE :search OR naam LIKE :search) ORDER BY gelezen ASC, datum DESC");
    $sth->bindParam(":search", $search);
    $sth->execute();
    
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
} else { // Haal anders alles op
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
    <input type="submit" name="submit" value="Verwijderen"/>
    <br/><br/>
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
			<td class="center"><?php echo(date("d-m-Y H:i:s", strtotime($row['datum']))); ?></td> <!-- Formatteer de datum naar onze standaarden -->
			<td class="center"><?php echo($row['gelezen'] == 1 ? "Ja" : "Nee"); ?></td>
		    </tr>
		<?php } ?>
		</table>
	<?php } ?>
</form>
