<?php

$dbh = connectToDatabase();
$style = "";
$statusText = "";

if(isset($_POST['submit'])) {
    
}


if(isset($_GET['name'])) {
    $sth = $dbh->prepare("SELECT id,titel,afzender,datum FROM berichten WHERE ontvanger=:name ORDER BY datum ASC");
    $sth->bindParam(":name", $_GET['name']);
    $sth->execute();
    
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    print_r($res);    
} elseif(isset($_POST['search'])) {
    // zoek
} else {
    $sth = $dbh->prepare("SELECT id,titel,afzender,datum FROM berichten ORDER BY datum ASC");
    $sth->execute();
    
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    print_r($res);
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
			<th>Datum verzonden</th>
		    </tr>
		    <tr>
			<?php foreach($res as $row) { ?>
			<td class="center"><input type="checkbox" name="id[]" value="<?php echo($row['id']); ?>"/></td>
			<td><?php echo($row['titel']);?></td>
			<td><?php echo($row['verstuurder']);?></td>
			<td><?php echo($row['datum']);?></td>
			<?php } ?>
		    </tr>
		</table>
	<?php } ?>
</form>
