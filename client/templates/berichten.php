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

  
if(isset($_POST['search'])) {
    $id = $_SESSION['clientid'];
    
    $search = "%".$_POST['search']."%";
    
    $sth = $dbh->prepare("SELECT id,titel,afzender,gelezen,datum FROM berichten WHERE ontvanger=:id AND titel LIKE :search ORDER BY gelezen ASC, datum DESC");
    $sth->bindParam(":id", $id);
    $sth->bindParam(":search", $search);
    $sth->execute();
    
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
} else {
    $id = $_SESSION['clientid'];
    
    $sth = $dbh->prepare("SELECT id,titel,afzender,gelezen,datum FROM berichten WHERE ontvanger=:id ORDER BY gelezen ASC, datum DESC");
    $sth->bindParam(":id", $id);
    $sth->execute();
    
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="<?php echo $style; ?>">
    <p><?php echo $statusText; ?></p>
</div>

<form action="" method="post">
    <input type="button" onclick="window.location = '/client/berichten/nieuw'" value="Nieuw"/>
    <br/><br/>
    <input type="text" name="search" placeholder="Zoeken..."/>
    <br/><br/>
    <?php 
	if(empty($res)) {
	    echo("Geen berichten om weer te geven.");
	    } else { ?>
		<table>
		    <tr>
			<th class="center">Titel</th>
			<th class="center">Datum verzonden</th>
			<th class="center">Gelezen</th>
		    </tr>
		<?php foreach($res as $row) { ?>
		    <tr>
			<td class="center"><a href="/client/bericht/<?php echo($row['id']);?>"><?php echo($row['titel']);?></a></td>
			<td class="center"><?php echo(date("d-m-Y H:i:s", strtotime($row['datum']))); ?></td>
			<td class="center"><?php echo($row['gelezen'] == 1 ? "Ja" : "Nee"); ?></td>
		    </tr>
		<?php } ?>

		</table>
	<?php } ?>
</form>
