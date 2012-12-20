<?php
/*
 * @author Jelle Kapitein
 * @klas ICT M1 E1
 * @projectGroup SSJ
 */

$dbh = connectToDatabase (); // Maak verbinding met de database
$statusText = "";
$style = "";

if (isset ( $_POST ['option'] ) && isset ( $_POST ['id'] )) {
	$id = $_POST ['id']; // Haal de ID array op
	$id = implode ( ',', $_POST ['id'] ); // Zet de array om in een string, uit
	                                      // elkaar gehouden door ,
	$id = mysql_real_escape_string ( $id ); // Maak de string veilig voor de
	                                        // database
	
	if ($_POST ['option'] == "Verwijder") { // Als er op de verwijder knop is
	                                        // gedrukt
		$sth = $dbh->prepare ( "DELETE FROM article WHERE ID IN(" . $id . ")" ); // Verwijder het artikel
		$result = $sth->execute ();
		if($result==true){
			$style='message_success';
			$statusText = "Artikel succesvol verwijderd.";
		}
		else{
			$style = 'message_error';
			$statusText = "Er is een fout opgetreden tijdens het verwijderen van het artikel, het artikel is niet verwijderd!";
		}
	} elseif ($_POST ['option'] == "Publiceer") {
		$sth = $dbh->prepare ( "UPDATE article SET published=1 WHERE ID IN (" . $id . ")" );
		$result = $sth->execute ();
		if($result==true){
			$style='message_success';
			$statusText = "Artikel succesvol gepubliceerd.";
		}
		else{
			$style = 'message_error';
			$statusText = "Er is een fout opgetreden tijdens het publiceren van het artikel, het artikel is niet gepubliceerd!";
		}
	} elseif ($_POST ['option'] == "Depubliceer") {
		$sth = $dbh->prepare ( "UPDATE article SET published=0 WHERE ID IN (" . $id . ")" );
		$result = $sth->execute ();
		if($result==true){
			$style='message_success';
			$statusText = "Artikel succesvol gedepubliceerd.";
		}
		else{
			$style = 'message_error';
			$statusText = "Er is een fout opgetreden tijdens het depubliceren van het artikel, het artikel is niet gedepubliceerd!";
		}
	}
}

if (isset ( $_GET ["case"] )) {
	if ($_GET ["case"] == "succes") {
		$statusText = "Artikel succesvol opgeslagen.";
		$style = "message_success";
	} else {
		$statusText = "Artikel niet succesvol opgeslagen.";
		$style = "message_error";
	}
}

$sth = $dbh->query ( "SELECT A.ID,title,C.name AS catname,date_added,date_edited,A.published FROM article A JOIN category C ON A.cat_id = C.cat_id ORDER BY ID" ); // Haal
                                                                                                                                                                   // alle
                                                                                                                                                                   // artikelen
                                                                                                                                                                   // uit
                                                                                                                                                                   // de
                                                                                                                                                                   // database
$sth->execute ();

$res = $sth->fetchAll ( PDO::FETCH_ASSOC );
?>
<div class="<?php echo $style; ?>">
	<p><?php echo $statusText; ?></p>
</div>
<form action="" method="post">
	<input type="button"
		onclick="window.location = '/admin/categorie/nieuw'"
		value="Nieuwe categorie" /> <input type="button"
		onclick="window.location = '/admin/artikel/nieuw'"
		value="Nieuw artikel" /> <input type="submit" name="option"
		value="Verwijder" /> <input type="submit" name="option"
		value="Publiceer" /> <input type="submit" name="option"
		value="Depubliceer" />

	<table class="hover">
		<tr>
			<th class="center"><input type="checkbox" id="checkall" value="" /></th>
			<th>Titel</th>
			<th>Categorie</th>
			<th>Datum aangemaakt</th>
			<th>Laatst gewijzigd</th>
			<th>Gepubliceerd</th>
		</tr>
	<?php
	foreach ( $res as $row ) { // Loop door SQL-resultaten
		echo ("<tr>");
		echo ("<td align=\"center\"><input type=\"checkbox\" value=" . $row ['ID'] . " name=id[]/></td>");
		echo ("<td><a href='/admin/artikel/bewerk/" . $row ['ID'] . "'>" . $row ['title'] . "</a></td>"); // Print
		                                                                                                  // de
		                                                                                                  // titel
		echo ("<td>" . $row ['catname'] . "</td>"); // Print de categorie
		echo ("<td align=\"center\">" . date ('d-m-Y H:i:s', strtotime ($row ['date_added'])) . "</td>"); // Print
		                                                                // de
		                                                                // datum
		echo ("<td align=\"center\">" . date ('d-m-Y H:i:s', strtotime ($row ['date_edited'])) . "</td>");
		echo ("<td align=\"center\">" . ($row ['published'] == 1 ? "Ja" : "Nee") . "</td>"); // Print
		                                                                                     // de
		                                                                                     // publicatiestatus
		echo ("</tr>");
	}
	?>
    </table>
</form>
