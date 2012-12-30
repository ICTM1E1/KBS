<?php
/*
 * @author Richard van den Hoorn @klas ICT M1 E1 @projectGroup SSJ
 */
// connecting to the database
$dbh = connectToDatabase ();

// define $statustext
$statusText = "";
$style = "";

// Check if case is defined
if(isset ( $_GET ["case"] )){
	if($_GET ["case"] == "succes"){
		$statusText = "Dienst succesvol opgeslagen.";
		$style = "message_success";
	}
	else{
		$statusText = "Dienst niet succesvol opgeslagen.";
		$style = "message_error";
	}
}

// Check if option is defined
if(isset ( $_POST ['option'] )){
	$statusText = "";
	
	if($_POST ['option'] == "Nieuw"){
		header ( "Location: /admin/diensten/nieuw" );
		exit ();
	}
	
	if(isset ( $_POST ['id'] )){
		// Get id from form in array
		$id = $_POST ['id'];
		// convert array to comma sepparated string
		$id = implode ( ", ", $id );
		// escape characters which are not allowed.
		// normally PDO does this, but it is not possible to get an array into
		// BindParam.
		$id = mysql_real_escape_string ( $id );
		
		if($_POST ['option'] == "Publiceer"){
			$sth = $dbh->prepare ( "UPDATE services SET published=1 WHERE service_id IN($id)" );
			$result = $sth->execute ();
			if($result == true){
				$style = 'message_success';
				$statusText = "Dienst succesvol gepubliceerd.";
			}
			else{
				$style = 'message_error';
				$statusText = "Er is een fout opgetreden tijdens het publiceren van de dienst, de dienst is niet gepubliceerd!";
			}
		}
		if($_POST ['option'] == "Depubliceer"){
			$sth = $dbh->prepare ( "UPDATE services SET published=0 WHERE service_id IN($id)" );
			$result = $sth->execute ();
			if($result == true){
				$style = 'message_success';
				$statusText = "Dienst succesvol gedepubliceerd.";
			}
			else{
				$style = 'message_error';
				$statusText = "Er is een fout opgetreden tijdens het depubliceren van de dienst, de dienst is niet gedepubliceerd!";
			}
		}
		if($_POST ['option'] == "Verwijderen"){
			$sth = $dbh->prepare ( "DELETE FROM services WHERE service_id IN($id)" );
			$result = $sth->execute ();
			if($result == true){
				$style = 'message_success';
				$statusText = "Dienst succesvol verwijderd.";
			}
			else{
				$style = 'message_error';
				$statusText = "Er is een fout opgetreden tijdens het verwijderen van de dienst, de dienst is niet verwijderd!";
			}
		}
	}
}

$sth = $dbh->query ( "SELECT * FROM services" ); // Haal alle diensten uit de
// database
$sth->execute ();
$res = $sth->fetchAll ( PDO::FETCH_ASSOC );

function haalartikelop($id) {
	$dbh = connectToDatabase ();
	$sth = $dbh->query ( "SELECT * FROM article WHERE ID=$id" ); // Haal alle diensten uit de database
	$sth->bindparam ( ':id', $id );
	$sth->execute ();
	$res = $sth->fetchAll ( PDO::FETCH_ASSOC );
	$title = '';
	
	foreach($res as $row){
		$title = $row ['title'];
	}
	
	return $title;
}

?>
<div class="<?php echo $style; ?>">
	<p><?php echo $statusText; ?></p>
</div>
<div>
	<form action="" method="post">
		<input name="option" type="submit" value="Nieuw"> <input name="option"
			type="submit" value="Publiceer"> <input name="option" type="submit"
			value="Depubliceer"> <input name="option" type="submit"
			value="Verwijderen">

		<!--Displaying the table-->
		<table class="hover">

			<!--Displaying the tablehead-->
			<thead>
				<tr>
					<th width="50" class="center"><input name="checkall"
						type="checkbox" value="check" id="checkall"></th>
					<th>Dienst</th>
					<th>Omschrijving</th>
					<th>Gepubliceerd</th>
					<th>Gelinkt artikel</th>
				</tr>
			</thead>

			<!--Displaying the tablebody-->
			<tbody>
	   		<?php
						// displaying all the values
						foreach($res as $row){
							?>
    	    	<tr>
					<td class="center"><input name="id[]" type=checkbox
						value="<?php echo $row['service_id']?>"></td>
					<td><a
						href="/admin/diensten/bewerk/<?php echo($row["service_id"]) ?>"><?php echo($row["servicename"]) ?></a></td>
					<td><?php echo($row["servicetext"]) ?></td>
					<td><?php echo($row['published'] == 1 ? "Ja" : "Nee")?></td>
					<td><?php
							
							$title = haalartikelop ( $row ["article_id"] );
							if($title != ''){
								?>
    						<a
						href="/admin/artikel/bewerk/<?php echo $row["article_id"] ?>"><?php echo haalartikelop($row["article_id"]) ?></a>
    						<?php
							
							}
							else{
								print ("Geen artikel geselecteerd") ;
							}
							?> 
    				</td>
				</tr>
		<?php } ?>
        	</tbody>
		</table>
	</form>
</div>