<?php
/*
 * @author Jelle Kapitein
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */

$dbh = connectToDatabase(); // Maak verbinding met de database
$statusText = "";
$style = "";

if (isset($_POST ['option']) && isset($_POST ['id'])) {
    $id = $_POST ['id']; // Haal de ID array op
    $id = implode(',', $_POST ['id']); // Zet de array om in een string, uit
    // elkaar gehouden door ,
    $id = mysql_real_escape_string($id); // Maak de string veilig voor de
    // database

    if ($_POST ['option'] == "Verwijder") { // Als er op de verwijder knop is
	// gedrukt
	$sth = $dbh->prepare("DELETE FROM article WHERE ID IN(" . $id . ")"); // Verwijder het artikel
	$result = $sth->execute();
	if ($result == true) { // Als de query is gelukt, laat succes bericht zien.
	    $style = 'message_success';
	    $statusText = "Artikel(en) succesvol verwijderd.";
	} else { // Anders faal bericht
	    $style = 'message_error';
	    $statusText = "Er is een fout opgetreden tijdens het verwijderen van het artikel, het artikel is niet verwijderd!";
	}
    } elseif ($_POST ['option'] == "Publiceer") {  // Publiceren
	$sth = $dbh->prepare("UPDATE article SET published=1 WHERE ID IN (" . $id . ")");
	$result = $sth->execute();
	if ($result == true) {
	    $style = 'message_success';
	    $statusText = "Artikel(en) succesvol gepubliceerd.";
	} else {
	    $style = 'message_error';
	    $statusText = "Er is een fout opgetreden tijdens het publiceren van het artikel, het artikel is niet gepubliceerd!";
	}
    } elseif ($_POST ['option'] == "Depubliceer") {  // Depubliceren
	$sth = $dbh->prepare("UPDATE article SET published=0 WHERE ID IN (" . $id . ")");
	$result = $sth->execute();
	if ($result == true) {
	    $style = 'message_success';
	    $statusText = "Artikel(en) succesvol gedepubliceerd.";
	} else {
	    $style = 'message_error';
	    $statusText = "Er is een fout opgetreden tijdens het depubliceren van het artikel, het artikel is niet gedepubliceerd!";
	}
    }
}

if (isset($_GET ["case"])) { // Als de gebruiker vanaf een andere pagina komt waar een query is uitgevoerd.
    if ($_GET ["case"] == "succes") {
	$statusText = "Artikel(en) succesvol opgeslagen.";
	$style = "message_success";
    } else {
	$statusText = "Artikel(en) niet succesvol opgeslagen.";
	$style = "message_error";
    }
}

if (isset($_POST['search']) && !empty($_POST['search'])) { // Als er ergens op gezocht is, haal die artikelen op
    $search = "%" . $_POST['search'] . "%";
    $sth = $dbh->prepare("SELECT A.ID,title,C.name AS catname,date_added,date_edited,A.published FROM article A JOIN category C ON A.cat_id = C.cat_id WHERE A.title LIKE :search ORDER BY A.cat_id,date_added");
    $sth->bindParam(":search", $search);
    $sth->execute();

    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
} else { // Haal anders alle artikelen op
    $sth = $dbh->query("SELECT A.ID,title,C.name AS catname,date_added,date_edited,A.published FROM article A JOIN category C ON A.cat_id = C.cat_id ORDER BY A.cat_id,date_added");
    $sth->execute();

    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
}
?>
<div class="<?php echo $style; ?>">
    <p><?php echo $statusText; ?></p>
</div>
<form action="" method="post">
    <input type="button" onclick="window.location = '/admin/artikel/nieuw'" value="Nieuw artikel" /> 
    <input type="submit" name="option" value="Verwijder" /> 
    <input type="submit" name="option" value="Publiceer" /> 
    <input type="submit" name="option" value="Depubliceer" />
    <br/><br/>
    <input type="text" placeholder="Zoeken..." name="search"/>
    <br/><br/>
    <?php
    if(empty($res)) {
    ?>    
	<i>Geen artikelen gevonden. <a href="/admin/artikel">Klik hier</a> om alle artikelen weer te geven of voeg er &egrave;&egrave;n toe.</i>
    <?php
    } else {
    ?>
	<table class="hover">
	    <tr>
		<th class="center"><input type="checkbox" id="checkall" value="" /></th>
		<th>Titel</th>
		<th>Categorie</th>
		<th class="center">Datum aangemaakt</th>
		<th class="center">Laatst gewijzigd</th>
		<th class="center">Gepubliceerd</th>
	    </tr>
	<?php
	foreach ($res as $row) { // Loop door SQL-resultaten
	    echo ("<tr>");
	    if($row ['title'] == 'Home')
	    {
	    	echo '<td></td>';
	    }
	    else {
	    	echo ("<td class=\"center\"><input type=\"checkbox\" value=" . $row ['ID'] . " name=id[]/></td>");
	    }
	    echo ("<td><a href='/admin/artikel/bewerk/" . $row ['ID'] . "'>" . $row ['title'] . "</a></td>"); // Print
	    // de
	    // titel
	    echo ("<td>" . $row ['catname'] . "</td>"); // Print de categorie
	    echo ("<td class=\"center\">" . date('d-m-Y H:i:s', strtotime($row ['date_added'])) . "</td>"); // Print
	    // de
	    // datum
	    echo ("<td class=\"center\">" . date('d-m-Y H:i:s', strtotime($row ['date_edited'])) . "</td>");
	    echo ("<td class=\"center\">" . ($row ['published'] == 1 ? "Ja" : "Nee") . "</td>"); // Print
	    // de
	    // publicatiestatus
	    echo ("</tr>");
	}}
	?>
    </table>
</form>
